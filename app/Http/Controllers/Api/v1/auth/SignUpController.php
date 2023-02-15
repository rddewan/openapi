<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends Controller
{
    /*
    * register user
    */
    public function register(Request $request): JsonResponse
    {

        //base url
        $base_url = env('APP_URL');

        $validateData = $request->validate([
            'name' => 'required|max:25',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
        ]);

        //create user
        $user = new User(
            [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
            ]
        );

        $user->save();

        return response()->json($user, Response::HTTP_CREATED);

    }

}
