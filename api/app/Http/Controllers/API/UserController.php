<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserFormRequest;

class UserController extends Controller
{
    public function index()
    {       
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(UserFormRequest $request)
    {        
        $user = $this->createUser($request);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $this->updateUser($request, $user);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {      
        $user->delete();
        return response()->json(null, 204);
    }

    private function createUser(UserFormRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    private function updateUser(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
    }
}
