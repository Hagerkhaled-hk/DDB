<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $admin = auth('sanctum')->user();

        if (! $admin->branch) {
            return response()->json([
                'message' => 'Admin does not have a branch assigned',
            ], 400);
        }

        $studentData = [
            "id"=>(string) Str::uuid(),
            'branch' => $admin->branch->name,
            'name' => $request->name,
            'grade' => $request->grade,
        ];

        $student = DB::transaction(function () use ($studentData) {
             $student= Student::on('branch_A')->create($studentData);
               Student::on('branch_B')->create($studentData);

            return $student;
        });

        return response()->json([
            'message' => 'Student created successfully',
            'data' => $student,
        ], 201);
    }

    public function index(): JsonResponse
    {

    $admin = auth('sanctum')->user();

        if (!$admin->branch) {
            return response()->json([
                'message' => 'Admin does not have a branch assigned',
            ], 400);
        }
        $branch = $admin->branch->name;

        $students = Student::on($branch)->get();

        return response()->json([
            'message' => 'Students retrieved successfully',
            'data' => $students->load("records"),
        ]);
    }
}