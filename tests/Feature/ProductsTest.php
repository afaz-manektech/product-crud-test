<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        $response = $this->actingAs($user)->json('POST', route('products.store'), array_merge($product->toArray(), [
            'photo' => UploadedFile::fake()->image('product.jpg')
        ]));

        Storage::disk('public')->assertExists('products/product.jpg');

        $response->assertSuccessful();
        $this->assertDatabaseCount('products', 1);
    }
}
