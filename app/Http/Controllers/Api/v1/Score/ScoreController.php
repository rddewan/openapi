<?php

namespace App\Http\Controllers\Api\v1\Score;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ScoreController extends Controller
{
    public function getScore(Request $request): JsonResponse
    {
        $data = DB::table('scores')
            ->where('user_id',$request->get('user_id'))
            ->where('level_id',$request->get('level_id'))
            ->max('score');

        return Response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'user_id' => 'required',
            'level_id' => 'required',
            'score' => 'required',
        ]);

        $isExist =  DB::table('scores')
            ->where('user_id', '=', $request->get('user_id'))
            ->where('level_id', '=', $request->get('level_id'))
            ->first();

        $data = new Score();
        $data->user_id = $request->get('user_id');
        $data->level_id = $request->get('level_id');
        $data->score = $request->get('score');
        $data->save();

        return Response()->json($data, Response::HTTP_CREATED);

    }

    /*
     * update a task
     */
    public function update(Request $request): JsonResponse
    {

        $request->validate([
            'user_id' => 'required',
            'level_id' => 'required',
            'score' => 'required',
        ]);

        $data =  DB::table('scores')
            ->where('id', '=', $request->get('id'))->first();

        $data->user_id = $request->get('user_id');
        $data->level_id = $request->get('level_id');
        $data->score = $request->get('score');
        $data->save();

        return Response()->json($data, Response::HTTP_CREATED);
    }
}
