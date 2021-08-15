<?php


namespace App\Http\Controllers;

use App\Forwardie\Constants\TaskDetailStatus;
use App\Forwardie\Helpers\TaskDetailHelper;
use App\Forwardie\Helpers\TransactionDetailHelper;
use App\Http\Resources\TaskDetailResource;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\TaskType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class TaskDetailController extends BaseAPIController
{
    /**
     * @OA\Get(
     *  path="/api/v1/taskDetail/{taskId}",
     *  summary="Gets Task Details",
     *  @OA\Parameter(name="taskId",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response="200",
     *    description="TaskDeails",
     *  )
     * )
     * @param $taskId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($taskId): \Illuminate\Http\JsonResponse
    {
        $taskDetails = TaskDetail::where('task_id', $taskId)
            ->where("status", TaskDetailStatus::APPROVED)
            ->orderBy("last_status_updated_on", "DESC")
            ->get();

        return $this->successResult(TaskDetailResource::collection($taskDetails));
    }

    /**
     * @OA\Post(
     *  path="/api/v1/taskDetail/{taskId}",
     *  summary="Creates Task Detail",
     *  @OA\Parameter(name="taskId",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(name="title",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="taskTypeId",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(name="sequenceNumber",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(name="taskPrerequisiteSequenceId",
     *    in="query",
     *    required=false,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response="200",
     *    description="Success result",
     *  ),
     *  @OA\Response(response="400",
     *    description="Bad Request",
     *  )
     * )
     *
     * @param $taskId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function create($taskId): JsonResponse
    {
        $validator = Validator::make(Request::all(), [
            'title'          => 'required|string',
            'taskTypeId'     => 'required|int',
            'sequenceNumber' => 'required|int',
            'prerequisites'  => 'array',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }

        $task = Task::find($taskId);

        if (!$task) {
            return $this->failureResult("Task is not valid");
        }

        $taskType = TaskType::find(Request::input('taskTypeId'));

        if (!$taskType) {
            return $this->failureResult("Task Type is not valid");
        }

        try {
            $additionalFieldsJson = TaskDetailHelper::additionalFieldCast($taskType->required_fields);
        } catch (\Exception $e) {
            return $this->failureResult($e->getMessage());
        }


        DB::beginTransaction();
        $taskDetail = new TaskDetail();
        $taskDetail->title = Request::input('title');
        $taskDetail->task_id = Request::input('taskId');
        $taskDetail->task_type_id = Request::input('taskTypeId');
        $taskDetail->sequence_number = Request::input('sequenceNumber');
        $taskDetail->additional_fields = $additionalFieldsJson;
        $taskDetail->prerequisites =  Request::input('prerequisites');

        try {
            $prerequisiteArray = TaskDetailHelper::prerequisiteArrayBuild($taskId, $taskDetail->prerequisites);
        } catch (\Exception $e) {
            return $this->failureResult($e->getMessage());
        }

        $taskDetail->prerequisites =  json_encode($prerequisiteArray, true);

        $taskDetail->save();

        if (!TaskDetailHelper::checkForFailure($prerequisiteArray, $taskDetail->id)) {
            DB::rollBack();
            return $this->failureResult("Task is a pre task in a sub task ");
        }

        DB::commit();

        return $this->successResult(new TaskDetailResource($taskDetail));
    }

    /**
     * @OA\Put (
     *  path="/api/v1/taskDetail/{taskDetailId}",
     *  summary="Update Task Detail",
     *  @OA\Parameter(name="title",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="taskTypeId",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(name="prerequisites",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="array",
     *      @OA\Items(
     *       type="integer",
     *       enum = {"available", "pending", "sold"},
     *     )
     *    )
     *  ),
     *  @OA\Response(response="200",
     *    description="Success result",
     *  ),
     *  @OA\Response(response="400",
     *    description="Bad Request",
     *  )
     * )
     *
     * @param $taskDetailId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function update($taskDetailId): JsonResponse
    {
        $validator = Validator::make(Request::all(), [
            'title'         => 'required|string',
            'taskTypeId'    => 'required|int',
            'prerequisites' => 'array',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }
        $taskDetail = TaskDetail::find($taskDetailId);

        if (!$taskDetail) {
            return $this->failureResult("Task Type is not valid");
        }

        DB::beginTransaction();

        $taskDetail->title = Request::input('title');
        $taskDetail->task_type_id = Request::input('taskTypeId');
        $taskDetail->prerequisites = Request::input('prerequisites');

        try {
            $prerequisiteArray = TaskDetailHelper::prerequisiteArrayBuild($taskDetail->task_id, $taskDetail->prerequisites);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failureResult($e->getMessage());
        }

        $taskDetail->prerequisites = json_encode($prerequisiteArray, true);

        $taskDetail->save();

        if (!TaskDetailHelper::checkForFailure($prerequisiteArray, $taskDetail->id)) {
            DB::rollBack();
            return $this->failureResult("Task is a pre task in a sub task ");
        }

        DB::commit();

        return $this->successResult(new TaskDetailResource($taskDetail));
    }

    /**
     * @OA\Get(
     *  path="/api/v1/taskDetail/nextStep{taskId}",
     *  summary="Finds Task Detail Next Step",
     *  @OA\Parameter(name="taskId",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response="200",
     *    description="Success result",
     *  ),
     *  @OA\Response(response="400",
     *    description="Bad Request",
     *  )
     * )
     *
     * @param $taskId
     *
     * @return JsonResponse
     */
    public function nextStep($taskId): JsonResponse
    {
        $task = Task::find($taskId);

        if (!$task) {
            return $this->failureResult("Task is not valid");
        }

        $approvedTaskDetails = TaskDetail::where('task_id', $taskId)
            ->where('status', TaskDetailStatus::APPROVED)
            ->pluck('id')
            ->toArray();

        $potentialNextSteps = TaskDetail::where('task_id', $taskId)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', TaskDetailStatus::CANCELED);
            })
            ->get();

        $nextSteps = [];
        foreach ($potentialNextSteps as $potentialNextStep) {
            $diff = array_diff(json_decode($potentialNextStep->prerequisites, true), $approvedTaskDetails);
            if (!$diff) {
                array_push($nextSteps, $potentialNextStep);
            }
        }

        return $this->successResult(TaskDetailResource::collection($nextSteps));
    }

}
