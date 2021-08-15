<?php

namespace App\Http\Resources;

use App\Models\TaskDetailHistory;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TaskDetailHistory
 *
 * @mixin TaskDetailHistory|TaskDetailHistory
 * @package TaskDetailHistory
 */
class TaskDetailHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'task'            => $this->task->title,
            'taskDetailId'    => $this->task_detail_id,
            'taskDetailTitle' => $this->taskDetail->title,
            'status'          => $this->status,
            'taskUpdatedOn'   => $this->task_updated_on,
            'createdAt'       => $this->created_at,
            'updatedAt'       => $this->updated_at,
        ];
    }
}
