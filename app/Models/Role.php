<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /************************************************************************************
     * CONSTANTS
     */

    CONST ROLE_EDITORIAL_DESIGN_MANAGER = 'editorial-design-managers';
    CONST ROLE_EDITORIAL_RESPONSIBLE = 'editorial-responsible';
    CONST ROLE_EDITORIAL_DIRECTOR = 'editorial-director';
    CONST ROLE_SALES_DIRECTOR = 'sales-director';
    CONST ROLE_CEO = 'ceo';
    CONST ROLE_ADMIN = 'admin';

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'roles';

    /************************************************************************************
     * RELATIONS
     */

    /**
     * Get related users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_role');
    }

}