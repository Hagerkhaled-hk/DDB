<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecordRequest;
use App\Models\Record;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    public function store(StoreRecordRequest $request): JsonResponse
    {
        $admin = auth('sanctum')->user();

        if (! $admin->branch) {
            return response()->json([
                'message' => 'Admin does not have a branch assigned',
            ], 400);
        }
        $studentA = Student::on("branch_A")->find($request->student_id);
        $studentB = Student::on("branch_B")->find($request->student_id);

        if (!($studentA && $studentB)) {
            return response()->json([
                'message' => 'Student not found or not in your branch',
            ], 404);
        }

        $adminBranch = $admin->branch->name;
        $studentBranch = $studentA ? $studentA->branch : $studentB->branch;

        if ($studentBranch !== $adminBranch) {
            return response()->json([
                'message' => 'You can only manage students in your branch',
            ], 403);
        }

        $record = DB::transaction(function () use ($request, $studentA, $studentB) {


   $record=$studentA->records()->create($request->only(['subject']));
        $studentB->records()->create($request->only(['subject']));
        return  $record;

});




        return response()->json([
            'message' => 'Record created successfully',
            'data' => $record->load('student'),
        ], 201);
    }
}