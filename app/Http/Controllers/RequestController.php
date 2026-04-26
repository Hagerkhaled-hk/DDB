<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestStatusRequest;
use App\Models\Request as RequestModel;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    public function store(StoreRequestRequest $request): JsonResponse
    {
        $admin = auth('sanctum')->user();
        if ($admin->branch_id == $request->validated('to_branch_id')) {
            return response()->json([
                                "success"=>false,

                'message' => 'can not an admin make request to its branch',
            ], 401);
        }

        $branch = $admin->branch->name;


        $student = Student::on($branch)->find($request->student_id);

        $adminBranch = $admin->branch->name;

        if ($student->branch != $adminBranch)
            return response()->json([
                        "success"=>false,

                'message' => 'this is admin can not make the request',
            ], 401);

        $requestData = [
            "id"=>(string) Str::uuid(),
            'student_id' => $request->validated('student_id'),
            'from_branch_id' =>  $admin->branch_id,
            'to_branch_id' => $request->validated('to_branch_id'),
        ];


        $requestModel = DB::transaction(function () use ($requestData) {
            $requestModel = RequestModel::on('branch_A')->create($requestData);
            RequestModel::on('branch_B')->create($requestData);

            return $requestModel;
        });

        return response()->json([
                            "success"=>true,

            'message' => 'Request created successfully',
            'data' => $requestModel
        ], 201);
    }

    public function myrequests(): JsonResponse
    {
        $admin = auth('sanctum')->user();

        $branch = match ($admin->branch->id) {
            1 => "branch_A",
            2 => "branch_B",
        };
        $requests = RequestModel::on($branch)->where('from_branch_id', $admin->branch->id)
            ->get();

        return response()->json([
                            "success"=>true,

            'message' => 'Requests retrieved successfully',
            'data' => $requests,
        ]);
    }
    public function index(): JsonResponse
    {
        $admin = auth('sanctum')->user();
        $branch = match ($admin->branch_id) {
            1 => "branch_A",
            2 => "branch_B",
        };

        $requests = RequestModel::on($branch)->get();

        return response()->json([
                            "success"=>true,

            'message' => 'Requests retrieved successfully',
            'data' => $requests,
        ]);
    }

    public function updateStatus(UpdateRequestStatusRequest $request): JsonResponse
    {
        $admin = auth('sanctum')->user();
               $branch = $admin->branch->name;


        $requestModel = RequestModel::on($branch)->find($request->requestId);

        if ($requestModel->to_branch_id !== $admin->branch_id) {
            return response()->json([
                "success"=>false,
                'message' => 'Unauthorized. Only the admin of the receiving branch can update status.',
            ], 403);
        }

        if($requestModel->status!=="pending")
            return response()->json([
                "success"=>false,
                'message' => "the request already ".$requestModel->status,
            ], 422);

          DB::transaction(function () use ($requestModel, $request,$branch) {
            $requestModel->update(['status' => $request->validated('status')]);
            RequestModel::on('branch_B')->where("id", $request->requestId)->update(['status' => $request->validated('status')]);

            if ($request->validated('status') === 'accepted') {
                Student::on("branch_A")->where("id",$requestModel->student_id)->update(['branch' => $branch]);
                Student::on("branch_B")->where("id",$requestModel->student_id)->update(['branch' =>$branch]);
            }
        });

        // Update student's branch_id if status is accepted


        return response()->json([
                            "success"=>true,
            'message' => 'Request updated successfully',
            'data' => $requestModel,
        ]);
    }
}
