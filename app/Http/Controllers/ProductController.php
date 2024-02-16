<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct()
    {
        try {
            $products = Product::all();
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data pelanggan', 'error' => $e], 500);
        }
    }

    public function searchProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            return new ProductResource($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mencari pelanggan'], 500);
        }
    }

    public function createProduct(Request $request)
    {
        try {
            $rules = [
                'NamaProduk' => 'required|unique:products,NamaProduk',
                'Harga' => 'required',
                'Stok' => 'required',
            ];

            $messages = [
                'NamaProduk.unique' => 'Nama produk sudah ada',
            ];

            $validatedData = $request->validate($rules, $messages);
            $product = Product::create($validatedData);
            return response()->json(['message' => 'Pelanggan berhasil ditambahkan', 'data' => new ProductResource($product)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }        
    }
    
    public function editProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $rules = [
                'Harga' => 'required',
                'Stok' => 'required',
            ];

            $request->validate($rules);
            $product->update([
                'Harga' => $request->Harga,
                'Stok' => $request->Stok
            ]);

            return response()->json(['message' => 'Pelanggan berhasil diperbarui', 'data' => new ProductResource($product)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Pelanggan berhasil dihapus'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus pelanggan'], 500);
        }
    }
}
