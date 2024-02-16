<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'Nama' => $this->NamaPelanggan,
            'Alamat' => $this->Alamat,
            'NomorTelepon' => $this->NomorTelepon,
            'Date' => Carbon::parse($this->created_at)->formatLocalized('%d %B %Y')
        ];
    }
}
