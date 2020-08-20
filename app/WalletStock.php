<?php

namespace App;

use App\Operation;
use App\Stock;
use App\Wallet;
use Illuminate\Database\Eloquent\Model;

class WalletStock extends Model
{
    protected $fillable = [
        'wallet_id',
        'stock_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function buy(int $amount, int $price): Operation
    {
        return $this->operations()->create([
            'type' => Operation::Buy,
            'quantity' => $amount,
            'price' => $price,
            'user_id' => $this->wallet->user_id,
        ]);
    }

    public function stockName()
    {
        return $this->stock->ticker;
    }

    public function averagePrice(): float
    {
        // average = ...(purchase price * quantity) / (number of stocks)

        $numberOfStocks = $this->quantity();

        $totalBought = $this->totalBought();

        return number_format($totalBought / $numberOfStocks, 2);
    }

    public function quantity(): int
    {
        return $this->operations()->buy()->sum('quantity') - $this->operations()->sell()->sum('quantity');
    }

    /**
     * The total bought of the shares in the local currency.
     *
     * @return integer
     */
    public function totalBought(): int
    {
        return $this->operations()
            ->buy()
            ->get()
            ->reduce(function ($carry, $operation) {
                return $carry + ($operation->price * $operation->quantity);
            }, 0);
    }

    public function profit()
    {
        // formula = profit per share * shares you have

        return ($this->averagePrice() - $this->stock->current_price) * $this->quantity();
    }
}
