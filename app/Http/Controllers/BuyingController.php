<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuyingDetailResource;
use App\Http\Resources\BuyingResource;
use App\Models\Buying;
use App\Models\DetailBuying;
use App\Models\Product; 
use App\Models\Customer;
use Illuminate\Http\Request;

class BuyingController extends Controller
{
    public function getAllBuying()
    {
        $buying = Buying::all();
        return BuyingResource::collection($buying);
    }
    public function getAllBuyingDetail()
    {
        $buyingDetails = DetailBuying::all();
        $mergedDetails = [];
    
        foreach ($buyingDetails as $detail) {
            $customerId = $detail->buying->PelangganID;
            if (!isset($mergedDetails[$customerId])) {
                $mergedDetails[$customerId] = [
                    'PenjualanID' => [
                        'id' => $detail->buying->id,
                        'TanggalPenjualan' => $detail->buying->TanggalPenjualan,
                        'TotalHarga' => $detail->buying->TotalHarga,
                        'PelangganID' => $customerId,
                    ],
                    'items' => [],
                ];
            }
            $mergedDetails[$customerId]['items'][] = [
                'ProdukID' => $detail->product,
                'JumlahProduk' => $detail->JumlahProduk,
                'Subtotal' => $detail->Subtotal,
            ];
        }
    
        $mergedDetails = array_values($mergedDetails);
    
        return response()->json(['data' => $mergedDetails]);
    }
    
    
    
    public function searchBuying($id)
    {
        $buying = Buying::findOrFail($id);
        return new BuyingResource($buying);
    }
    public function searchDetail($id)
    {
        $buyingDetails = DetailBuying::where('PenjualanID', $id)->get();
    
        if ($buyingDetails->isEmpty()) {
            return response()->json(['message' => 'Detail pembelian tidak ditemukan'], 404);
        }
    

        $mergedDetails = [];
    
        foreach ($buyingDetails as $detail) {
            $customerId = $detail->buying->PelangganID;
            if (!isset($mergedDetails[$customerId])) {
                $mergedDetails[$customerId] = [
                    'PenjualanID' => [
                        'id' => $detail->buying->id,
                        'TanggalPenjualan' => $detail->buying->TanggalPenjualan,
                        'TotalHarga' => $detail->buying->TotalHarga,
                        'PelangganID' => $customerId,
                    ],
                    'items' => [],
                ];
            }
            $mergedDetails[$customerId]['items'][] = [
                'ProdukID' => $detail->product,
                'JumlahProduk' => $detail->JumlahProduk,
                'Subtotal' => $detail->Subtotal,
            ];
        }
    
        $mergedDetails = array_values($mergedDetails);
    
        return response()->json(['data' => $mergedDetails]);
    }
    
    public function createBuying(Request $request)
    {
        $request->validate([
            'TanggalPenjualan' => 'required|date',
            'PelangganID' => 'required|exists:customers,id',
            'details' => 'required|array',
            'details.*.ProdukID' => 'required|exists:products,id',
            'details.*.JumlahProduk' => 'required|integer|min:1', // Pastikan jumlah produk positif
        ]);

        // Validasi stok produk
        $errors = [];
        foreach ($request->input('details') as $detail) {
            $product = Product::findOrFail($detail['ProdukID']);
            if ($detail['JumlahProduk'] > $product->Stok) {
                $errors[] = "Stok produk {$product->NamaProduk} tidak mencukupi";
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        // Simpan pembelian jika validasi berhasil
        $totalHarga = 0;

        // Membuat objek pembelian
        $buying = new Buying();
        $buying->TanggalPenjualan = $request->input('TanggalPenjualan');
        $buying->TotalHarga = $totalHarga;
        $buying->PelangganID = $request->input('PelangganID');
        $buying->save();

        foreach ($request->input('details') as $detail) {
            $product = Product::findOrFail($detail['ProdukID']);
            $subtotal = $product->Harga * $detail['JumlahProduk'];
            $totalHarga += $subtotal;

            // Simpan detail pembelian
            $detailBuying = new DetailBuying();
            $detailBuying->PenjualanID = $buying->id;
            $detailBuying->ProdukID = $detail['ProdukID'];
            $detailBuying->JumlahProduk = $detail['JumlahProduk'];
            $detailBuying->Subtotal = $subtotal; // Menggunakan hasil perkalian langsung
            $detailBuying->save();

            // Kurangi stok produk
            $product->Stok -= $detail['JumlahProduk'];
            $product->save();
        }

        // Perbarui total harga setelah semua detail pembelian ditambahkan
        $buying->TotalHarga = $totalHarga;
        $buying->save();

        return response()->json(['message' => 'Penjualan berhasil ditambahkan', 'Data' => $buying], 201);
    }


    
}
