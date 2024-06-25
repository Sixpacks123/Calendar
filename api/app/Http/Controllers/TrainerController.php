<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    public function index()
    {
        // Récupérer tous les utilisateurs avec le rôle de formateur
        $trainers = User::role('trainer')->get();

        // Transformer la collection pour inclure explicitement l'ID
        $trainers = $trainers->map(function ($trainer) {
            return [
                'id' => $trainer->id,
                'name' => $trainer->name,
                'email' => $trainer->email,
                'created_at' => $trainer->created_at,
                'updated_at' => $trainer->updated_at,
                'ulid' => $trainer->ulid,
                'avatar' => $trainer->avatar,
                'has_password' => $trainer->has_password,
            ];
        });

        return response()->json($trainers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trainer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $trainer->assignRole('trainer');

        return response()->json($trainer, 201);
    }

    public function show($id)
    {
        $trainer = User::with('roles')->find($id);
        if (!$trainer || !$trainer->hasRole('trainer')) {
            return response()->json(['message' => 'Trainer not found'], 404);
        }
        return response()->json($trainer->only(['id', 'name', 'email', 'roles']));
    }

    public function update(Request $request, $id)
    {
        $trainer = User::find($id);
        if (!$trainer || !$trainer->hasRole('trainer')) {
            return response()->json(['message' => 'Trainer not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$id,
            'password' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trainer->update($request->all());

        return response()->json($trainer);
    }

    public function destroy($id)
    {
        $trainer = User::find($id);
        if (!$trainer || !$trainer->hasRole('trainer')) {
            return response()->json(['message' => 'Trainer not found'], 404);
        }

        $trainer->delete();

        return response()->json(['message' => 'Trainer deleted'], 204);
    }
}
