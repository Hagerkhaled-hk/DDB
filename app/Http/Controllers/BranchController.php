<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{

public function create(Request $request)
{
    $validated=$request->validate([
        "name"=>"required|string",
        "location"=>"required|string",
    ]);

       DB::transaction(function () use ( $validated) {
         Branch::on("branch_A")->create($validated);
          Branch::on("branch_B")->create($validated);


        });
    try
    {

return response()->json([
    "success"=>true,
            "message"=>"created successfully"
        ],201);
    }
    catch(\Exception $e)
    {
        return response()->json([
            "message"=>$e->getMessage()
        ],500);
    }

}

public function show(Request $request)
{
    try
    {
        $branches = Branch::on("branch_A")->get(['id', 'name', 'location']);
return response()->json([
    "success"=>true,
            "message"=>"returned successfully",
            "Data"=>$branches
        ],200);
    }
    catch(\Exception $e)
    {
        return response()->json([
                "success"=>false,

            "message"=>$e->getMessage()
        ],500);
    }

}
}
