<?php

namespace App;

use App\Stock;
use App\User;
use App\WalletStock;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stocks()
    {
        return $this->hasMany(WalletStock::class);
    }

    public function addStock(Stock $stock)
    {
        return $this->stocks()->create([
            'stock_id' => $stock->id,
        ]);
    }
}
