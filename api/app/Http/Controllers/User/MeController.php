<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function getMe()
    {
    	if(auth()->check())
    	{
    		$user = auth()->user();
    		$user->created_at_human = $user->created_at->diffForHumans();

    		return new UserResource($user);

    		//return response()->json(["user" => auth()->user()], 200);
    	}
    		return response()->json(null, 401);
    }
}
