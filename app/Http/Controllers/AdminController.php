<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
  public function login(LoginAdminRequest $request): JsonResponse
{
    $admin = Admin::on('branch_A')
        ->where('email', $request->validated('email'))
        ->first();

    if (!$admin || !Hash::check($request->validated('password'), $admin->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }


    return response()->json([
        'token' => $admin->createToken('auth', ['admin'])->plainTextToken,
    ]);
}
}
