<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'imdb_id' => $this->imdb_id,
            'title' => $this->title,
            'year' => $this->year,
            'poster' => $this->poster,
            'released' => $this->released,
            'plot' => $this->plot,
            'type' => $this->type,
            'runtime' => $this->runtime,
            'directors' => $this->directors->pluck('name'),
        ];
    }
}
