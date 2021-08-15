<?php

namespace App\Http\Resources;

use App\Models\Task;
use App\Models\TaskDetail;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Task
 *
 * @mixin Task|Task
 * @package TaskDetails
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @package Task
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'title'     => $this->title,
            'createdAt' => $this->created_at,
        ];
    }
}
