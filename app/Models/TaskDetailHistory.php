<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TaskType
 *
 * @property int        $id
 * @property int        $task_id
 * @property int        $task_detail_id
 * @property string     $status
 * @property TaskDetail $taskDetail
 * @property Task       $task
 * @property Carbon     $task_updated_on
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 * @package Modules\Coin\Entities
 */
class TaskDetailHistory extends Model
{
    protected $table = 'task_detail_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'task_detail_id',
        'status',
        'task_updated_on',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'task_id'         => 'int',
        'task_detail_id'  => 'int',
        'status'          => 'string',
        'task_updated_on' => 'datetime',
    ];

    /**
     * Get TaskDetail
     *
     * @return BelongsTo|TaskDetail
     */
    public function taskDetail()
    {
        return $this->belongsTo(TaskDetail::class, 'task_detail_id', 'id');
    }

    /**
     * Get Task
     *
     * @return BelongsTo|Task
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

}
