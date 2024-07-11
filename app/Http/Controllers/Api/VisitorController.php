<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all visitors order by id desc
        $visitors = Visitor::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Visitors retrieved successfully',
            'data' => VisitorResource::collection($visitors),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|string',
            'date_visited' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }

        Visitor::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Visitor created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $visitors = Visitor::find($id);

        if (!$visitors) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|string',
            'date_visited' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }

        $visitors->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Visitor updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $visitors = Visitor::find($id);

        if (!$visitors) {
            return response()->json([
                'status' => false,
                'message' => 'Visitor not found',
            ], 200);
        }

        $visitors->delete();

        return response()->json([
            'status' => true,
            'message' => 'Visitor deleted successfully',
        ], 200);
    }
}
