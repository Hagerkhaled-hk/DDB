<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function signup(SignupRequest $request): JsonResponse
    {

     $admin =  DB::transaction(function () use ( $request) {
        $admin  = Admin::on("branch_A")->create($request->validated());
          Admin::on("branch_B")->create($request->validated());

            return $admin ;
        });

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin->get(["id",'name',"email"])->load('branch'),
        ], 201);
    }


}
