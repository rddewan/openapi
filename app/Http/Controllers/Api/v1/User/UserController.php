<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    function getUser($id): JsonResponse
    {
        $data = DB::table('users')
            ->where('id',$id)
            ->select('id','name','email')
            ->first();

        return Response::json($data, ResponseAlias::HTTP_OK);

    }


    function delete(Request $request): JsonResponse
    {

        // delete user
        $delete = DB::table('users')
            ->where('id',$request->get('user_id'))
            ->delete();

        return Response::json([
            'delete' => $delete != 0,

        ], ResponseAlias::HTTP_OK);


    }
}
