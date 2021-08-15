<?php


namespace App\Forwardie\Helpers;


use App\Forwardie\Constants\TaskDetailStatus;
use App\Models\TaskDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class TaskDetailHelper
{
    /**
     *
     * @param $prerequisiteArray
     * @param $taskDetailId
     *
     * @return bool
     */
    public static function checkForFailure($prerequisiteArray, $taskDetailId): bool
    {
        $flippedArray = array_flip($prerequisiteArray);

        if (isset($flippedArray[$taskDetailId])) {
            return false;
        }

        return true;

    }

    /**
     *
     * @param $required_fields
     *
     * @return string
     * @throws \Exception ()
     */
    public static function additionalFieldCast($required_fields): string
    {
        $additionalFields = [];
        foreach ($required_fields as $input => $value) {
            if (!Request::input($input)) {
                throw new \Exception($input . " field must be added for task Type");
            }

            if (gettype(Request::input($input)) !== $value) {
                throw new \Exception($input . " field must be " . $value . " type");
            }
            $additionalFields[$input] = Request::input($input);
        }
        return json_encode($additionalFields, true);
    }

    /**
     * @param       $taskId
     * @param array $prerequisites
     *
     * @return bool
     */
    public static function Updatable($taskId, array $prerequisites): bool
    {
        $notApprovedTaskDetail = TaskDetail::where('task_id', $taskId)
            ->whereIn('id', $prerequisites)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', TaskDetailStatus::CANCELED);
            })
            ->first();

        if ($notApprovedTaskDetail) {
            return false;
        }

        return true;
    }

    /**
     * @param      $taskId
     * @param null $prerequisites
     *
     * @return array
     * @throws \Exception
     */
    public static function prerequisiteArrayBuild($taskId, $prerequisites): array
    {
        $taskDetails = TaskDetail::whereIn('id', $prerequisites)
            ->where('task_id', $taskId)
            ->pluck('prerequisites');

        if (count($taskDetails) !== count($prerequisites)) {
            throw new \Exception("There are unmatched Task Details");
        }

        $prerequisiteArray = $prerequisites;
        foreach ($taskDetails as $tasks) {
            $array = json_decode($tasks, true);
            if (count($array) > 0) {
                $prerequisiteArray = array_merge($prerequisites, json_decode($tasks, true));
            }
        }

        return $prerequisiteArray;
    }
}
