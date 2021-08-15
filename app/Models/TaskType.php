<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskType
 *
 * @property int $id
 * @property string $title
 * @property array $required_fields
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package Modules\Coin\Entities
 */
class TaskType extends Model
{
    protected $table = 'task_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'required_fields',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'required_fields' => 'array',
    ];
}
