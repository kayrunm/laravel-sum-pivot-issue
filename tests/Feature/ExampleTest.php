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

    /**
     * This test is currently failing. From what I can see, it's because of line 625 in QueriesRelationships,
     * where it tries to add the "relationCountHash" as a prefix to the column if the current query's `from`
     * matches the $relation's base query's `from`. Because of both queries being for the `transactions`
     * table, this condition evaluates to true and we end up with the prefix.
     *
     * The error:
     *    SQLSTATE[HY000]: General error: 1 no such column: laravel_reserved_0.transaction_transaction.amount
     *
     *    (SQL:
     *        select
     *            "transactions".*,
     *            (
     *                select sum("laravel_reserved_0"."transaction_transaction"."amount")
     *                from "transactions" as "laravel_reserved_0"
     *                inner join "transaction_transaction" on "laravel_reserved_0"."id" = "transaction_transaction"."allocated_to_id"
     *                where "transactions"."id" = "transaction_transaction"."allocated_from_id"
     *            ) as "total_allocated"
     *        from "transactions"
     *        limit 1
     *    )
     */
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
