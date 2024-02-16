<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buying extends Model
{
    protected $table = 'buyings';

    protected $fillable = [
        'TanggalPenjualan',
        'TotalHarga',
        'PelangganID',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'PelangganID');
    }

    public function details()
    {
        return $this->hasMany(DetailBuying::class, 'PenjualanID');
    }
}
