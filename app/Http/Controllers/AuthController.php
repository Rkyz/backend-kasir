<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function getAllAccount()
    {
        $acc = User::all();
        return AuthResource::collection($acc);
    }

    public function login(Request $request)
    {
        try {
            if (!$request->email || !$request->password) {
                throw new Exception("password dan email harus ada");
            }

            if (!Auth::attempt($request->all())) {
                throw new Exception("password atau email salah");
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => '200',
                'msg' => 'Berhasil login',
                'token' => $user->createToken('login')->plainTextToken
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => '500',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function checkLogin(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                throw new Exception('User belum login');
            }

            return response()->json([
                'status' => '200',
                'user' => 'user'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => '500',
                'msg' => $e->getMessage()
            ]);
        }
    }
}
