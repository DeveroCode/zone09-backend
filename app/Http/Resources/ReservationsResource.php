<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationsResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'total_hours' => $this->total_hours,
            'folio' => $this->folio,
            'pc' => $this->computers ? $this->computers->name : null,
            'start' => $this->start,
            'end' => $this->end,
            'total_amount' => $this->total_amount,
            'day_reservation' => $this->created_at->format('d/m/Y'),
            'loan' => $this->loan
        ];
    }
}