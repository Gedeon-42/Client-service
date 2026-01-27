<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'due_date' => $this->due_date,
            'completed_at' => optional($this->completed_at)->toISOString(),

            'created_at' => $this->created_at->format('M d, Y H:i'),
            'updated_at' => optional($this->updated_at->format('M d, Y H:i'))
        ];
    }
}
