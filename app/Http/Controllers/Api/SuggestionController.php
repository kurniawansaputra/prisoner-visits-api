<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuggestionResource;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all suggestions order by id desc
        $suggestions = Suggestion::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Suggestions retrieved successfully',
            'data' => SuggestionResource::collection($suggestions),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }

        Suggestion::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Suggestion created successfully',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
