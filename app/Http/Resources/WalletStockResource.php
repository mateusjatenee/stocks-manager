<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->resource->stockName(),
            'average_price' => $this->resource->averagePrice(),
            'quantity' => $this->resource->quantity(),
            'profit' => $this->resource->profit(),
        ];
    }
}
