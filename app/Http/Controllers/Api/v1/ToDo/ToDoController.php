<?php

namespace App\Http\Controllers\Api\v1\ToDo;

use App\Http\Controllers\Controller;
use App\Models\ToDo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ToDoController extends Controller
{
    /*
     * get task by pagination
     */
    public function getToDos($id): JsonResponse
    {
        $data = DB::table('to_dos')
            ->where('user_id',$id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return Response()->json($data, Response::HTTP_OK);
    }

    public function getToDoList($id): JsonResponse
    {
        $data = DB::table('to_dos')
            ->where('user_id',$id)
            ->orderBy('id', 'desc')
            ->get();

        return Response()->json($data, Response::HTTP_OK);
    }


    /*
     * search task by title with like operator
     */
    public function searchToDo($query): JsonResponse
    {
        $data = DB::table('to_dos')
            ->where('title', 'like', $query . '%')
            ->orderBy('id', 'desc')
            ->get();

        return Response()->json($data, Response::HTTP_OK);

    }

    /*
     * get task by id
     */
    public function getToDo($id): JsonResponse
    {
        $data = DB::table('to_dos')
            ->where('id', '>', $id)
            ->first();

        return Response()->json($data, Response::HTTP_OK);

    }

    /*
     * get the task > then the passed arguments id
     */
    public function getTaskGreaterThenId($id): JsonResponse
    {

        $data = DB::table('to_dos')
            ->where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->get();

        return Response()->json($data, Response::HTTP_OK);

    }

    /*
     * create a task
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'body' => 'required',
            'note' => 'required',
            'status' => 'required'
        ]);

        $id = DB::table('to_dos')
            ->where('id', '=', $request->get('id'))
            ->insertGetId(
                [
                    'user_id' => $request->get('user_id'),
                    'title' => $request->get('title'),
                    'body' => $request->get('body'),
                    'note' => $request->get('note'),
                    'status' => $request->get('status'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

        $data = DB::table('to_dos')
            ->where('id', '=', $id)
            ->first();

        return Response()->json($data, Response::HTTP_CREATED);

    }

    /*
     * update a task
     */
    public function update(Request $request): JsonResponse
    {

        $request->validate([
            'id' => 'required',
            'user_id' => 'required',
            'title' => 'required',
            'body' => 'required',
            'status' => 'required'
        ]);

        DB::table('to_dos')
            ->where('id', '=', $request->get('id'))
            ->update(
                [
                    'user_id' => $request->get('user_id'),
                    'title' => $request->get('title'),
                    'body' => $request->get('body'),
                    'note' => $request->get('note'),
                    'status' => $request->get('status'),
                    'updated_at' => Carbon::now(),

                ]
            );

        $data = DB::table('to_dos')
            ->where('id', '=', $request->get('id'))
            ->first();

        return Response()->json($data, Response::HTTP_CREATED);
    }

    /*
     * delete task by id
     */
    public function destroy(Request $request): JsonResponse
    {

        $task =  DB::table('to_dos')->where('id', '=', $request->get('id'))->first();

        if (!empty($task)) {

            if ($task->user_id == $request->get('user_id')) {
                $result = DB::table('to_dos')
                    ->where('id', '=', $request->get('id'))
                    ->where('user_id', '=', $request->get('user_id'))
                    ->delete();

                return Response()->json(['deleted' => $result != 0], Response::HTTP_OK);
            }

            return response()->json(
                [
                    'error' => 'Task does not belong to you.'
                ], Response::HTTP_NOT_FOUND);

        }

        return response()->json(
            [
                'error' => 'Task does not exist.',
            ], Response::HTTP_NOT_FOUND);

    }

}
