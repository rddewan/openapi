<?php

namespace App\Http\Controllers\Api\v1\Level;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LevelController extends Controller
{
    public function getLevel($id): JsonResponse
    {
        $data = DB::table('levels')
            ->where('user_id',$id)
            ->max('level');

        return Response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'user_id' => 'required',
            'score_id' => 'required',
            'level' => 'required',
        ]);

        $data = new Level();
        $data->user_id = $request->get('user_id');
        $data->score_id = $request->get('score_id');
        $data->level = $request->get('level');
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
            'score_id' => 'required',
            'level' => 'required',
        ]);

        $data =  DB::table('levels')
            ->where('id', '=', $request->get('id'))->first();

        $data->user_id = $request->get('user_id');
        $data->score_id = $request->get('score_id');
        $data->level = $request->get('level');
        $data->save();

        return Response()->json($data, Response::HTTP_CREATED);
    }
}
