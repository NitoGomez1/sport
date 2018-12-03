<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_all_products()
    {
        $product = factory(Product::class)->create();

        $this->getJson('api/products')
            ->assertSuccessful()
            ->assertJsonFragment(['name' => $product->name]);
    }

    /** @test */
    public function it_shows_a_product()
    {
        $product = factory(Product::class)->create();
        $this->getJson('api/products/' . $product->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $product->name,
            ]);
    }

    /** @test */
    public function it_returns_404_if_product_does_not_exist()
    {
        $this->getJson('api/products' . 10)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_returns_201_if_category_is_successfully_created()
    {
        $this->postJson('api/products', factory(Product::class)->raw())
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_cannot_create_two_products_with_the_same_name()
    {
        $this->markTestSkipped();

        factory(Product::class)->create(['name' => 'foo']);
        $this->assertCount(1, Product::all());

        $product = factory(Product::class)->make(['name' => 'foo'])->toArray();

        dd($product);

        $this->postJson('api/products', $product)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function it_does_not_show_deleted_products_when_browse_all()
    {
        $product = factory(Product::class)->create();
        $product->delete();

        $this->getJson('api/products')->assertJsonMissing(['name' => $product->name]);
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }

    /** @test */
    public function it_does_not_show_deleted_products_when_show_one()
    {
        $product = factory(Product::class)->create();
        $product->delete();

        $this->getJson('api/products/' . $product->id)->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('products', ['name' => $product->name]);
    }

    /** @test */
    public function it_updates_a_product()
    {
        $product = factory(Product::class)->create();

        $this->patchJson('api/products/' . $product->id, ['name' => 'Updated'])
            ->assertSuccessful();

        $this->assertEquals('Updated', $product->fresh()->name);
    }

    /** @test */
    public function it_does_not_update_a_product_if_there_is_another_with_the_same_name()
    {
        $this->markTestSkipped();

        factory(Product::class)->create(['name' => 'Foo']);
        $product = factory(Product::class)->create(['name' => 'Bar']);

        $this->patchJson('api/categories/' . $product->id, ['name' => 'Foo'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Bar', $product->fresh()->name);
    }

    /** @test */
    public function it_deletes_a_product()
    {
        $product = factory(Product::class)->create();
        $this->deleteJson('api/products/' . $product->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $product->name,
            ]);
    }

    /** @test */
    public function it_returns_a_404_if_tries_to_delete_a_product_that_does_not_exist()
    {
        $this->deleteJson('api/products/' . 100)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
