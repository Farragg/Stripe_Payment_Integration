<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_can_add_new_product()
    {
        $data = [
            'name' => 'Test',
            'price' => 120.00,
            'quantity' => 2,
        ];

        // post the data to store route
        $response = $this->postJson(route('product.store'), $data);

        // Check if response JSON structure matches expected output
        $response->assertJsonStructure([
            'product' => [
                'id',
                'name',
                'price',
                'quantity',
                'created_at',
                'updated_at',
                'category_id'
            ]
        ]);

        // make sure the product was added to the database
        $this->assertDatabaseHas('products', [
            'name' => 'Test',
            'price' => 120.00,
            'quantity' => 2,
        ]);
    }

}
