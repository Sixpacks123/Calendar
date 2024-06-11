<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    // Afficher tous les meetings
    public function index()
    {
        $meetings = Meeting::all();
        return response()->json($meetings);
    }

    // Créer un nouveau meeting
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_hour' => 'required|date_format:Y-m-d H:i:s',
            'end_hour' => 'required|date_format:Y-m-d H:i:s|after:start_hour',
            'break_time' => 'required|integer',
            'location' => 'required|string',
            'school_id' => 'required|exists:schools,id',
            'admin_id' => 'required|exists:users,id',
            'trainer_id' => 'required|exists:users,id',
            'module_id' => 'nullable|exists:modules,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $meeting = Meeting::create($request->all());
        return response()->json($meeting, 201);
    }

    // Afficher un meeting spécifique
    public function show($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }
        return response()->json($meeting);
    }

    // Mettre à jour un meeting
    public function update(Request $request, $id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'start_hour' => 'date_format:Y-m-d H:i:s',
            'end_hour' => 'date_format:Y-m-d H:i:s|after:start_hour',
            'break_time' => 'integer',
            'location' => 'string',
            'school_id' => 'exists:schools,id',
            'admin_id' => 'exists:users,id',
            'trainer_id' => 'exists:users,id',
            'module_id' => 'nullable|exists:modules,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $meeting->update($request->all());
        return response()->json($meeting);
    }

    // Supprimer un meeting
    public function destroy($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $meeting->delete();
        return response()->json(['message' => 'Meeting deleted'], 204);
    }

    // Assigner un admin à un meeting
    public function assignAdmin(Request $request, $id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $meeting->update(['admin_id' => $request->admin_id]);
        return response()->json(['message' => 'Admin assigned successfully']);
    }

    // Assigner un trainer à un meeting
    public function assignTrainer(Request $request, $id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $meeting->update(['trainer_id' => $request->trainer_id]);
        return response()->json(['message' => 'Trainer assigned successfully']);
    }
}
