<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function test_pivot_sum_with_different_tables()
    {
        $order = Order::factory()->create();

        $order->products()->save(Product::factory()->make(), ['quantity' => 2]);
        $order->products()->save(Product::factory()->make(), ['quantity' => 3]);
        $order->products()->save(Product::factory()->make(), ['quantity' => 4]);

        $result = Order::query()
            ->withSum('products as total_quantity', 'order_product.quantity')
            ->first();

        $this->assertSame(9, $result->total_quantity);
    }

    public function test_pivot_sum_with_same_table()
    {
        $transaction = Transaction::factory()->create(['total' => 900]);

        $transaction->allocatedTo()->save(Transaction::factory()->make(['total' => -200]), ['amount' => 200]);
        $transaction->allocatedTo()->save(Transaction::factory()->make(['total' => -300]), ['amount' => 300]);
        $transaction->allocatedTo()->save(Transaction::factory()->make(['total' => -400]), ['amount' => 400]);

        $result = Transaction::query()
            ->withSum('allocatedTo as total_allocated', 'transaction_transaction.amount')
            ->first();

        $this->assertSame(900, $result->total_allocated);
    }
}
