<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{      
    public function apiAccount(Request $request) : object  {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ],[
            'name.required' => 'Name wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib di isi',
            'password.min' => 'Password minimal 8 karakter'
        ]);
        try {
            $user = $request->only('name', 'email', 'password');
            $user['password'] = Hash::make($user['password']);
            $user = User::create($user);
            
            $user->assignRole('superadmin');
    
            return response()->json([
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal Menambahkan User di POS',
                'data' => $th->getMessage()
            ], 400);
        }
    }

    
}