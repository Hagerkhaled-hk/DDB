<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{

public function create(Request $request)
{
    $validated=$request->validate([
        "name"=>"required|string",
        "location"=>"required|string",
    ]);
    try
    {

           Branch::create($validated);
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
        $branches = Branch::all(['id', 'name', 'location']);
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
