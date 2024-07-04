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
        $this->authorize('viewAny', User::class);
        
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(UserFormRequest $request)
    {
        $this->authorize('create', User::class);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return new UserResource($user);
    }

    public function update(UserFormRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
    
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $user->delete();
        return response()->json(null, 204);
    }
}
