<?php

namespace App\Http\Controllers;

use App\Forwardie\Helpers\TaskDetailHelper;
use App\Http\Resources\TaskDetailHistoryResource;
use App\Models\TaskDetail;
use App\Models\TaskDetailHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class TaskDetailHistoryController extends BaseAPIController
{

    /**
     * @OA\Get(
     *  path="/api/v1/taskDetailHistory/{taskId}",
     *  summary="Gets Task Detail History",
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
     * @return JsonResponse
     */
    public function index($taskId)
    {
        $taskDetailHistory = TaskDetailHistory::where('task_id', $taskId)
            ->orderBy('task_updated_on', 'DESC')
            ->get();

        return $this->successResult(TaskDetailHistoryResource::collection($taskDetailHistory));
    }

    /**
     * @OA\Post(
     *  path="/api/v1/taskDetailHistory/{taskDetailId}",
     *  summary="Creates Task Detail History Status only get CANCELED OR APPROVED",
     *  @OA\Parameter(name="taskDetailId",
     *    in="path",
     *    required=true,
     *    @OA\Schema(type="integer")
     *  ),
     *  @OA\Parameter(name="status",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="taskUpdatedOn",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="Date")
     *  ),
     *  @OA\Response(response="200",
     *    description="Success result",
     *  ),
     *  @OA\Response(response="400",
     *    description="Bad Request",
     *  )
     * )
     * @param $taskDetailId
     *
     * @return JsonResponse
     */
    public function create($taskDetailId)
    {
        $validator = Validator::make(Request::all(), [
            'status'        => 'required|string|in:APPROVED,CANCELED',
            'taskUpdatedOn' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }

        $taskDetail = TaskDetail::find($taskDetailId);

        if (!$taskDetail) {
            return $this->failureResult("Task Detail could not be found");
        }

        if ($taskDetail->status == Request::input('status')) {
            return $this->failureResult("You can not update for the same status");
        }

        if(!TaskDetailHelper::Updatable($taskDetail->task_id, json_decode($taskDetail->prerequisites, true))) {
            return $this->failureResult("You haven't approved required pre-tasks");
        }

        try {
            DB::beginTransaction();

            $taskHistory = new TaskDetailHistory();
            $taskHistory->task_id = $taskDetail->task_id;
            $taskHistory->task_detail_id = $taskDetailId;
            $taskHistory->status = Request::input('status');
            $taskHistory->task_updated_on = Request::input('taskUpdatedOn');
            $taskHistory->save();

            $taskDetail->status = Request::input('status');
            $taskDetail->last_status_updated_on = Request::input('taskUpdatedOn');
            $taskDetail->save();
            DB::commit();

            return $this->successResult($taskHistory);
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task History Add Failed.', [
                'group'   => 'Task History',
                'message' => 'Task History Add Failed',
            ]);

            return $this->failureResult("An Error occurred");
        }
    }
}
