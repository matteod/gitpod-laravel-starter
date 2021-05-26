<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Sector extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sectors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'key', 'description'];

    /************************************************************************************
     * RELATIONS
     */

    /**
     * Get the author
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /************************************************************************************
     * FUNCTIONS
     */

    /**
     * Get cached sectors
     *
     * @return mixed
     */
    public function getAll()
    {
        return Cache::remember('sectors', 86400, function () {
            return Sector::all();
        });

    }
}