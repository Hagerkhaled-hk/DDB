<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function signup(SignupRequest $request): JsonResponse
    {
        $admin = Admin::create($request->validated());

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin->load('branch'),
            'token' => $admin->createToken('API Token')->plainTextToken,
        ], 201);
    }

 
}
