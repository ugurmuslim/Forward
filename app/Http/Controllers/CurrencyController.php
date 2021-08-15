<?php

namespace App\Http\Controllers;

use App\Forwardie\Constants\CacheKeys;
use App\Forwardie\DB\Redis;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends BaseAPIController
{

    /**
     * Cache TTL [Second]
     */
    const CACHE_TTL = 86400;

    /**
     * @OA\Get(
     *  path="/api/v1/currency/",
     *  operationId="Currency",
     *  summary="Gets Currencies",
     *  @OA\Response(response="200",
     *    description="Currencies",
     *  )
     * )
     */
    public function index()
    {
        if (Cache::has(CacheKeys::CURRENCY)) {
            $currencies = Cache::get(CacheKeys::CURRENCY);
        } else {
            $currencies = Currency::all();
            Cache::put(CacheKeys::CURRENCY, $currencies, self::CACHE_TTL);
        }

        return $this->successResult(CurrencyResource::collection($currencies));
    }


    /**
     * @OA\Post(
     *  path="/api/v1/currency/",
     *  operationId="Currencies",
     *  summary="Creates Currency",
     *  @OA\Parameter(name="id",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="symbol",
     *    in="query",
     *    required=false,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="200",
     *    description="Tasks",
     *  ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     * )
     */
    public function create()
    {
        $validator = Validator::make(Request::all(), [
            'id'     => 'required|unique:currencies|string',
            'symbol' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->failureResult($validator->errors());
        }

        $lock = Cache::lock('currency_lock', 2);
        if ($lock) {

            $currency = new Currency();
            $currency->id = Request::input('id');
            $currency->symbol = Request::input('symbol');

            $currency->save();

            if (Cache::has(CacheKeys::CURRENCY)) {
                Cache::forget(CacheKeys::CURRENCY);
            }

            $currencies = Currency::all();
            Cache::put(CacheKeys::CURRENCY, $currencies, self::CACHE_TTL);
            $lock->release();
        } else {
            $currencies = Currency::all();
        }

        return $this->successResult($currencies);

    }
}
