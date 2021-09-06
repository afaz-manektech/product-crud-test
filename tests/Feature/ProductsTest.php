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

    /** @test */
    public function it_updates_products()
    {
        /** @var User */
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->json('PUT', route('products.update', $product), [
            'name' => 'Updated product',
            'year' => 1923,
            'photo' => UploadedFile::fake()->image('product-updated.jpg')
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseCount('products', 1);
        $product->refresh();

        Storage::disk('public')->assertExists('products/product-updated.jpg');

        $this->assertSame($product->name, 'Updated product');
        $this->assertSame((int) $product->year, 1923);
    }

    /** @test */
    public function it_deletes_product()
    {
        /** @var User */
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertDatabaseCount('products', 1);

        $response = $this->actingAs($user)->json('DELETE', route('products.destroy', $product));
        $response->assertSuccessful();
        $this->assertDatabaseCount('products', 0);
    }

    /** @test */
    public function it_lists_products()
    {
        /** @var User */
        $user = User::factory()->create();
        $products = Product::factory()->count(10)->create([
            'user_id' => $user->id,
            'photo' => 'products/product-image.jpg'
        ]);

        $response = $this->actingAs($user)->json('GET', route('products.index'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            [
                'name',
                'year',
                'photo_url'
            ]
        ]);
    }
}
