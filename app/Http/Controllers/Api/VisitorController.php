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
            'prisoner_number' => 'required|string',
            'luggage' => 'required|string',
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
            'prisoner_number' => 'required|string',
            'luggage' => 'required|string',
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

    // get count of visitors every month and year
    public function countVisitors(Request $request)
    {
        $visitors = Visitor::selectRaw('COUNT(*) as total, EXTRACT(MONTH FROM date_visited) as month, EXTRACT(YEAR FROM date_visited) as year')
            ->groupBy('month', 'year')
            ->orderByRaw('year, month')
            ->get();

        // Map of month numbers to month names
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        // Transform the result to replace month numbers with month names
        $visitors->transform(function ($item) use ($months) {
            $item->month = $months[$item->month];
            $item->year = substr($item->year, -2); // Get the last two digits of the year
            return $item;
        });
        return response()->json([
            'status' => true,
            'message' => 'Visitors count retrieved successfully',
            'data' => $visitors,
        ], 200);
    }

    // get visistors by month and year and order by date visited
    public function getVisitorsByMonthAndYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 200);
        }

        $visitors = Visitor::whereMonth('date_visited', $request->month)
            ->whereYear('date_visited', $request->year)
            ->orderBy('date_visited', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Visitors retrieved successfully',
            'data' => VisitorResource::collection($visitors),
        ], 200);
    }
}
