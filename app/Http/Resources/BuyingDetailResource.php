<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $penjualanID = $this['PenjualanID'] ?? null;
        $items = $this['items'] ?? [];

        return [
            'PenjualanID' => [
                'id' => $penjualanID['id'] ?? null,
                'TanggalPenjualan' => $penjualanID['TanggalPenjualan'] ?? null,
                'TotalHarga' => $penjualanID['TotalHarga'] ?? null,
                'PelangganID' => $penjualanID['PelangganID'] ?? null,
            ],
            'items' => BuyingDetailResource::collection($items),
        ];
    }
}
