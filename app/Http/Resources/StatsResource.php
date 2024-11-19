<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->formatTitle($this['key']),
            'cuantity' => $this['value'],
        ];
    }

    private function formatTitle(string $key): string
    {
        $titles = [
            'ganacias_mensuales' => 'Ganancias Mensuales',
            'ganancias_diarias' => 'Ganancias Diarias',
            'rentas_mensuales' => 'Rentas Mensuales',
            'ganancias_totales' => 'Ganancias Netas',
            'percentage' => 'Porcentajes',
        ];

        return $titles[$key] ?? 'Sin Título';
    }
}