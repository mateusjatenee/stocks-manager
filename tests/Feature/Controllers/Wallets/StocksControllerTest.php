<?php

namespace Tests\Feature\Controllers\Wallets;

use App\Stock;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StocksControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_the_correct_average_price_for_a_given_stock()
    {
        // average = ...(purchase price * quantity) / (number of stocks)

        // Buys 10 apple stocks at 10 dolalrs
        // Buys 30 apple stocks at 15 dollars
        // Average price should be 13.75

        $user = factory(User::class)->create();
        $stock = factory(Stock::class)->create([
            'ticker' => 'AAPL',
            'market' => 'NASDAQ',
            'current_price' => 12,
        ]);

        $wallet = factory(Wallet::class)->create([
            'name' => 'Long Term Wallet',
            'user_id' => $user,
        ]);

        $walletStock = $wallet->addStock($stock);

        // Buys 10 apple stocks at 10 dolalrs
        // Buys 30 apple stocks at 15 dollars
        // Average price should be 13.75
        $firstBuyOperation = $walletStock->buy(10, 10);
        $secondBuyOperation = $walletStock->buy(30, 15);

        $response = $this
            ->actingAs($user)
            ->get(route('wallets.stocks.show', [$wallet, $walletStock]));

        // profit should be
        // (13.75 - 12) * 40 = 70

        // $response->assertResource(WalletStockResource::make($walletStock));
        $response->assertJson([
            'data' => [
                'name' => 'AAPL',
                'average_price' => 13.75,
                'quantity' => 40,
                'profit' => 70,
            ],
        ]);
    }
}
