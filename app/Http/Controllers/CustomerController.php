<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Dotenv\Exception\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function getAllCustomer()
    {
        try {
            $customers = Customer::all();
            return CustomerResource::collection($customers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data pelanggan', 'error' => $e], 500);
        }
    }

    public function searchCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return new CustomerResource($customer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mencari pelanggan'], 500);
        }
    }

    public function createCustomer(Request $request)
    {
        try {
            $rules = [
                'NamaPelanggan' => 'required|unique:customers,NamaPelanggan',
                'Alamat' => 'required',
                'NomorTelepon' => 'required|unique:customers,NomorTelepon',
            ];

            $messages = [
                'NamaPelanggan.unique' => 'Nama pelanggan sudah ada',
                'NomorTelepon.unique' => 'Nomor telepon sudah ada',
            ];

            $validatedData = $request->validate($rules, $messages);
            $customer = Customer::create($validatedData);
            return response()->json(['message' => 'Pelanggan berhasil ditambahkan', 'data' => new CustomerResource($customer)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }        
    }
    
    public function editCustomer(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $rules = [
                'Alamat' => 'required',
                'NomorTelepon' => 'required',
            ];

            $request->validate($rules);
            $customer->update([
                'Alamat' => $request->Alamat,
                'NomorTelepon' => $request->NomorTelepon
            ]);

            return response()->json(['message' => 'Pelanggan berhasil diperbarui', 'data' => new CustomerResource($customer)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['message' => 'Pelanggan berhasil dihapus'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus pelanggan'], 500);
        }
    }
}
