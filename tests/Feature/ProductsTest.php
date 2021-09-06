<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_stores_products()
    {
        /** @var User */
        $user = User::factory()->create();
        $product = Product::factory()->make();

        $file = UploadedFile::fake();
        $response = $this->actingAs($user)->json('POST', route('products.store'), array_merge($product->toArray(), [
            'file' => $file
        ]));

        $response->assertSuccessful();
        $this->assertDatabaseCount('products', 1);
    }
}
