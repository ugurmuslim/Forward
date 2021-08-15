<?php

namespace App\Http\Resources;

use App\Models\TaskDetail;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TaskDetails
 *
 * @mixin TaskDetail|TaskDetail
 * @package TaskDetails
 */
class TaskDetailResource extends JsonResource
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
            'id'               => $this->id,
            'title'            => $this->title,
            'task'             => $this->task->title,
            'prerequisites'    => $this->prerequisites,
            'additionalFields' => $this->additional_fields,
            'taskType'         => $this->taskType->title,
            'status'           => $this->status,
            'lastStatusUpdate' => $this->last_status_updated_on,
            'createdAt'        => $this->created_at,
            'updatedAt'        => $this->updated_at,
        ];
    }
}
