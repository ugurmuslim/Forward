<?php

namespace App\Http\Resources;

use App\Models\TaskType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TaskType
 *
 * @mixin TaskType|TaskType
 * @package TaskTypes
 */
class TaskTypeResource extends JsonResource
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
            'id'             => $this->id,
            'title'          => $this->title,
            'requiredFields' => $this->required_fields,
            'createdAt'      => $this->created_at,
        ];
    }
}
