<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    // Afficher tous les meetings avec les détails associés (admin, trainer, module, school)
    public function index()
    {
        $meetings = Meeting::with(['admin', 'trainer', 'module', 'school'])->get();
        return response()->json($meetings);
    }

    // Créer un nouveau meeting
public function store(Request $request)
{
    \Log::info('Request data:', $request->all());

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

    // Assurez-vous que les dates sont correctement formatées
    $startHour = $request->input('start_hour');
    $endHour = $request->input('end_hour');

    \Log::info('Formatted Start Hour:', ['start_hour' => $startHour]);
    \Log::info('Formatted End Hour:', ['end_hour' => $endHour]);

    // Create the meeting with the formatted date values
    $meeting = Meeting::create([
        'start_hour' => $startHour,
        'end_hour' => $endHour,
        'break_time' => $request->input('break_time'),
        'location' => $request->input('location'),
        'school_id' => $request->input('school_id'),
        'admin_id' => $request->input('admin_id'),
        'trainer_id' => $request->input('trainer_id'),
        'module_id' => $request->input('module_id'),
    ]);

    // Log the created meeting
    \Log::info('Created Meeting:', $meeting->toArray());

    // Fetch the meeting again from the database to check stored values
    $storedMeeting = Meeting::find($meeting->id);
    \Log::info('Stored Meeting:', $storedMeeting->toArray());

    return response()->json($storedMeeting, 201);
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
    // Récupérer les meetings pour un formateur spécifique
    public function getMeetingsByTrainer($trainerId)
    {
        $meetings = Meeting::where('trainer_id', $trainerId)->get();
        return response()->json($meetings);
    }
}
