<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    /**
     * Display a listing of the schools.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $schools = School::all();
        return response()->json(['schools' => $schools], 200);
    }

    /**
     * Store a newly created school in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $school = School::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return response()->json(['school' => $school], 201);
    }

    /**
     * Display the specified school.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $school = School::find($id);
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }
        return response()->json(['school' => $school], 200);
    }

    /**
     * Update the specified school in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $school = School::find($id);
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $school->name = $request->name;
        $school->address = $request->address;
        $school->save();

        return response()->json(['school' => $school], 200);
    }

    /**
     * Remove the specified school from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $school = School::find($id);
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $school->delete();

        return response()->json(['message' => 'School deleted successfully'], 200);
    }
}
