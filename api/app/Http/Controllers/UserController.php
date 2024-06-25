<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index(): JsonResponse
    {
        $users = User::with('roles')->get();

        // Ensure 'id' is explicitly included in the response
        $users = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'has_password' => $user->has_password,
                'roles' => $user->roles,
                'ulid' => $user->ulid,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        return response()->json([
            'ok' => true,
            'users' => $users
        ]);
    }

    /**
     * Create a new user with role.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,trainer']
        ]);

        // Generate a random password
        $randomPassword = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($randomPassword),
        ]);

        $user->ulid = Str::ulid()->toBase32();
        $user->save();

        $user->assignRole($request->role);

        // Send email with the generated password
        Mail::send('emails.welcome', ['user' => $user, 'password' => $randomPassword], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Welcome to the platform');
        });

        return response()->json([
            'ok' => true,
            'user' => $user
        ], 201);
    }

    /**
     * Get a specific user.
     */
    public function show($id): JsonResponse
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'ok' => true,
            'user' => $user
        ]);
    }

    /**
     * Update a user.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'role' => ['sometimes', 'string', 'in:admin,trainer']
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return response()->json([
            'ok' => true,
            'user' => $user
        ]);
    }

    /**
     * Delete a user.
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'ok' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
