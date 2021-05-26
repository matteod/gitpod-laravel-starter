<?php

namespace App\Models;

use App\Jobs\StoreEditorialProjectLogJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use function Illuminate\Events\queueable;

class EditorialProject extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'editorial_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'pages',
        'price',
        'cost',
        'sector_id',
        'author_id',
        'is_approved_by_ceo',
        'is_approved_by_editorial_director',
        'is_approved_by_editorial_responsible',
        'is_approved_by_sales_director',
    ];

    protected $casts = [
        'is_approved_by_ceo' => 'boolean',
        'is_approved_by_editorial_director' => 'boolean',
        'is_approved_by_editorial_responsible' => 'boolean',
        'is_approved_by_sales_director' => 'boolean',
    ];

    public static function booted()
    {
        static::created(queueable(function ($editorial_project) {
            StoreEditorialProjectLogJob::dispatchAfterResponse($editorial_project->author_id, $editorial_project->id, EditorialProjectLog::ACTION_CREATE);
        }));

        static::updated(queueable(function ($editorial_project) {
            StoreEditorialProjectLogJob::dispatchAfterResponse(Auth::id(), $editorial_project->id, EditorialProjectLog::ACTION_UPDATE);
        }));

        static::deleted(queueable(function ($editorial_project) {
            StoreEditorialProjectLogJob::dispatchAfterResponse(Auth::id(), $editorial_project->id, EditorialProjectLog::ACTION_DESTROY);
        }));
    }


    /************************************************************************************
     * RELATIONS
     */

    /**
     * Get the author
     *
     * @return HasOne
     */
    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }

    /**
     * Get the sector
     *
     * @return HasOne
     */
    public function sector(): HasOne
    {
        return $this->hasOne(Sector::class, 'id');
    }

    /**
     * Get logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(EditorialProjectLog::class, 'editorial_project_id');
    }

    /************************************************************************************
     * SCOPES
     */

    /**
     * Filters projects by user role
     *
     * @param $query
     * @param $role
     * @return mixed
     */
    public function scopeByUserRole($query, $role)
    {
        switch ($role) {
            case Role::ROLE_CEO:
                return $query->where('is_approved_by_editorial_director', true)
                    ->where('is_approved_by_editorial_responsible', true)
                    ->where('is_approved_by_sales_director', true)
                    ->where('is_approved_by_ceo', false);
            case Role::ROLE_EDITORIAL_DIRECTOR:
                return $query->where('is_approved_by_editorial_director', false)
                    ->where('is_approved_by_editorial_responsible', true)
                    ->where('is_approved_by_sales_director', true);
            case Role::ROLE_SALES_DIRECTOR:
                return $query->where('is_approved_by_sales_director', false);
            case Role::ROLE_EDITORIAL_RESPONSIBLE:
                return $query->where('is_approved_by_editorial_responsible', false);
            default:
                return $query->where('is_approved_by_editorial_director', false)
                    ->where('is_approved_by_editorial_responsible', false)
                    ->where('is_approved_by_sales_director', false)
                    ->where('is_approved_by_ceo', false);
        }
    }

    /**
     * Filter data by errors
     *
     * @param $query
     */
    public function scopeHaveErrors($query)
    {
        $query->where(function ($q) {
            $q->where('is_approved_by_ceo', true)->where(function ($sub) {
                $sub->where('is_approved_by_editorial_responsible', false)
                    ->orWhere('is_approved_by_sales_director', false)
                    ->orWhere('is_approved_by_editorial_director', false);
            })->orWhere('is_approved_by_editorial_director', true)->where(function ($sub) {
                $sub->where('is_approved_by_sales_director', false)
                    ->orWhere('is_approved_by_editorial_director', false);
            });
        });
    }

    /************************************************************************************
     * FUNCTIONS
     */

    /**
     * Check if user can update flags
     *
     * @param $role
     * @return bool
     */
    public function userRoleCanUpdateFlags($role): bool
    {
        switch ($role) {
            case Role::ROLE_CEO:
                return $this->is_approved_by_editorial_director && $this->is_approved_by_editorial_responsible && $this->is_approved_by_sales_director;
            case Role::ROLE_EDITORIAL_DIRECTOR:
                return !$this->is_approved_by_ceo && $this->is_approved_by_editorial_responsible && $this->is_approved_by_sales_director;
            case Role::ROLE_SALES_DIRECTOR:
            case Role::ROLE_EDITORIAL_RESPONSIBLE:
                return !$this->is_approved_by_ceo && !$this->is_approved_by_editorial_director;
            default:
                return false;
        }
    }

    /**
     * Count errors for
     *
     * @return int
     */
    public function countErrors(): int
    {
        $errors = 0;
        $roles = [Role::ROLE_CEO, Role::ROLE_EDITORIAL_DIRECTOR, Role::ROLE_SALES_DIRECTOR, Role::ROLE_EDITORIAL_RESPONSIBLE];

        foreach ($roles as $role) {
            if (!$this->userRoleCanUpdateFlags($role)) {
                $errors++;
            }
        }

        return $errors;
    }

}