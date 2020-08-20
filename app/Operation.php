<?php

namespace App;

use App\Stock;
use App\User;
use App\WalletStock;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    const Buy = 'BUY';
    const Sell = 'SELL';

    protected $fillable = [
        'type',
        'quantity',
        'price',
        'wallet_stock_id',
        'user_id',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function walletStock()
    {
        return $this->belongsTo(WalletStock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBuy($query)
    {
        return $query->where('type', self::Buy);
    }

    public function scopeSell($query)
    {
        return $query->where('type', self::Sell);
    }
}
