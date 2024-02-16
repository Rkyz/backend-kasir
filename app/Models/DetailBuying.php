<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBuying extends Model
{
    protected $table = 'detail_buyings';

    protected $fillable = [
        'PenjualanID',
        'ProdukID',
        'JumlahProduk',
        'Subtotal',
    ];

    public function buying()
    {
        return $this->belongsTo(Buying::class, 'PenjualanID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProdukID');
    }
}
