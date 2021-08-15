<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TaskType
 *
 * @property int      $id
 * @property string   $title
 * @property int      $task_id
 * @property string   $task_type_id
 * @property int      $sequence_number
 * @property string   $status
 * @property array    $task_prerequisite_sequence_id
 * @property array    $additional_fields
 * @property TaskType $taskType
 * @property array    $prerequisites
 * @property Task     $task
 * @property Carbon   $last_status_updated_on
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 * @package Modules\Coin\Entities
 */
class TaskDetail extends Model
{
    protected $table = 'task_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'title',
        'task_type_id',
        'sequence_number',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'task_id'         => 'int',
        'title'           => 'string',
        'task_type_id'    => 'int',
        'sequence_number' => 'int',
        'status'          => 'string',
    ];


    /**
     * Get Task
     *
     * @return BelongsTo|Task
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
     * Get Task
     *
     * @return BelongsTo|TaskType
     */
    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id', 'id');
    }

}
