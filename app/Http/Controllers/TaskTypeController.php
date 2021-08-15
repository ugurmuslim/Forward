<?php


namespace App\Http\Controllers;


use App\Http\Resources\TaskTypeResource;
use App\Models\TaskType;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class TaskTypeController extends BaseAPIController
{
    /**
     * @OA\Get(
     *  path="/api/v1/tasktypes/",
     *  operationId="Task Types",
     *  summary="Gets Task Types",
     *  @OA\Response(response="200",
     *    description="Tasks",
     *  )
     * )
     */
    public function index()
    {
        $taskTypes = TaskType::all();
        return $this->successResult(TaskTypeResource::collection($taskTypes));
    }


    /**
     * @OA\Post(
     *  path="/api/v1/tasktypes/",
     *  operationId="Task Types",
     *  summary="Creates Task Types",
     *  @OA\Parameter(name="title",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="requiredFields",
     *    in="query",
     *    required=false,
     *    @OA\Schema(type="json")
     *  ),
     *  @OA\Response(response="200",
     *    description="Tasks",
     *  ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     * )
     */
    public function create()
    {
        $validator = Validator::make(Request::all(), [
            'title'           => 'required|string',
            'required_fields' => 'json',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }

        $taskType = new TaskType();
        $taskType->title = Request::input('title');
        $taskType->required_fields = Request::input('requiredFields');
        $taskType->save();

        return $this->successResult();

    }
}
