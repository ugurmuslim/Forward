<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Currencies
 *
 * @mixin Currency|Currency
 * @package Currencies
 */
class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'symbol'    => $this->symbol,
            'createdAt' => $this->created_at,
        ];
    }
}
