<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditorialProjectLog extends Model
{
    use HasFactory;

    CONST ACTION_CREATE = 'CREATE';
    CONST ACTION_UPDATE = 'UPDATE';
    CONST ACTION_DESTROY = 'DESTROY';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'editorial_project_log';
    public $timestamps = false;

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
        return $this->belongsTo(User::class,'author_id');
    }
}