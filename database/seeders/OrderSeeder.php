<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class OrderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** @var Order $order */
        $order = Order::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $order->products()->save(
                Product::factory()->make(),
                ['quantity' => rand(1, 5)],
            );
        }
    }
}
