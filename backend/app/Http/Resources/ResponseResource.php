<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "date" => $this->date,
            "user" => $this->user,
            "answers" => $this->answers->reduce(function ($array, $answer) {
                $array[$answer->question->name] = $answer->value;
                return $array;
            }, [])
        ];
    }
}
