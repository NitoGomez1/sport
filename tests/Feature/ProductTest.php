<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
        factory(Product::class)->create(['name' => 'foo']);
        $this->assertCount(1, Product::all());

        $product = factory(Product::class)->make(['name' => 'foo'])->toArray();

        $this->postJson('api/products', $product)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function product_source_url_has_to_be_a_valid_url()
    {
        $data = factory(Product::class)->make(['source' => 'invalid_url']);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function it_cannot_create_two_products_with_the_same_url_source()
    {
        factory(Product::class)->create(['name' => 'http://foo.test']);
        $this->assertCount(1, Product::all());

        $product = factory(Product::class)->make(['name' => 'http://foo.test'])->toArray();

        $this->postJson('api/products', $product)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function product_image_is_mandatory()
    {
        $data = factory(Product::class)->make(['image' => null]);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function product_image_must_be_a_valid_url()
    {
        $data = factory(Product::class)->make(['image' => 'invalid_image_url']);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function product_price_is_mandatory()
    {
        $data = factory(Product::class)->make(['price' => null]);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function product_price_should_be_a_valid_number()
    {
        $data = factory(Product::class)->make(['price' => 'not a number']);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function product_price_should_be_less_than_10000_euros()
    {
        $data = factory(Product::class)->make(['price' => 10000]);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function product_gtin_can_be_null()
    {
        $data = factory(Product::class)->make(['gtin' => null]);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_CREATED);
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function product_gtin_must_be_unique()
    {
        $gtin = $this->faker->ean13;
        factory(Product::class)->create(['gtin' => $gtin]);
        $this->assertCount(1, Product::all());

        $product = factory(Product::class)->make(['gtin' => $gtin])->toArray();

        $this->postJson('api/products', $product)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function product_material_cannot_be_null()
    {
        $data = factory(Product::class)->make(['material' => null]);
        $this->postJson('api/products', $data->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertCount(0, Product::all());
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
