<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class LoginController extends Controller
{
    /*
     * login user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        //get user info
        $userInfo = User::query()->where('email', '=', $request->get('email'))->first();

        if ($userInfo) {

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {

                return response()->json(
                    ['error' => 'Unauthorised Access. Please check your login detail'],
                    ResponseAlias::HTTP_UNAUTHORIZED
                );

            }

            $user = $request->user();
            $user->save();

            $tokenResult = $user->createToken('PersonalAccessToken');

            return response()->json([
                'access_token' => "Bearer " . $tokenResult->plainTextToken,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);


        }
        else {
            return response()->json([
                'error' => 'Sorry no user account match with this email.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

    }
}
