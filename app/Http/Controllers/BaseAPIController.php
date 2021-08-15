<?php

namespace App\Http\Controllers;

use App\Forwardie\Traits\TResponse;
/** @OA\Info(title="Forwardie Api", version="0.1") */

class BaseAPIController extends Controller
{
   use TResponse;
}
