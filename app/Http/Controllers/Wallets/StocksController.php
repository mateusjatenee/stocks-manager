<?php

namespace App\Http\Controllers\Wallets;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletStockResource;
use App\Wallet;

class StocksController extends Controller
{
    public function show(Wallet $wallet, $walletStockId)
    {
        $walletStock = $wallet->stocks()->findOrFail($walletStockId);

        return WalletStockResource::make($walletStock);
    }
}
