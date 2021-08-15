<?php


namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseAPIController
{
    /**
     * @OA\Get(
     *  path="/api/v1/task/",
     *  operationId="Tasks",
     *  summary="Gets Tasks",
     *  @OA\Response(response="200",
     *    description="Tasks",
     *  )
     * )
     */
    public function index()
    {
        return $this->successResult(TaskResource::collection(Task::all()));
    }

    /**
     * @OA\Post(
     *  path="/api/v1/task",
     *  summary="Creates Task",
     *  @OA\Parameter(name="title",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="200",
     *    description="Success result",
     *  ),
     *  @OA\Response(response="400",
     *    description="Bad Request",
     *  )
     * )
     *
     * @return JsonResponse
     */
    public function create()
    {
        $validator = Validator::make(Request::all(), [
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }

        $taskType = new Task();
        $taskType->title = Request::input('title');
        $taskType->save();

        return $this->successResult($taskType);
    }
}
