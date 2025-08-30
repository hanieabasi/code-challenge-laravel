<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'project_id' => $this->project_id,
            'assignee' => $this->assignee ? ['id' => $this->assigned_to, 'name' => $this->assignee->name] : null,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
