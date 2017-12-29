<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;

class ApiUserController extends Controller
{
    public function index()
    {
    	$users = User::all();
    	return response()->json($users);
    }

    public function update($id, UserRequest $request)
    {
    	$user = User::find($id);
    	$user->update($request->json()->all());
    	
    	return response()->json($user);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->json()->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
    }
}
