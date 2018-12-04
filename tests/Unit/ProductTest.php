<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLevel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_brand()
    {
        $product = factory(Product::class)->make();
        $this->assertInstanceOf(BelongsTo::class, $product->brand());
        $this->assertInstanceOf(Brand::class, $product->brand);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $product = factory(Product::class)->make();
        $this->assertInstanceOf(BelongsTo::class, $product->category());
        $this->assertInstanceOf(Category::class, $product->category);
    }

    /** @test */
    public function it_belongs_to_a_level()
    {
        $product = factory(Product::class)->make();
        $this->assertInstanceOf(BelongsTo::class, $product->level());
        $this->assertInstanceOf(ProductLevel::class, $product->level);
    }

    /** @test */
    public function it_needs_a_brand()
    {
        $this->expectException(QueryException::class);
        factory(Product::class)->create(['brand_id' => null]);
    }

    /** @test */
    public function it_needs_an_existing_brand()
    {
        $this->expectException(QueryException::class);
        factory(Product::class)->create(['brand_id' => 9]);
    }

    /** @test */
    public function it_needs_a_category()
    {
        $this->expectException(QueryException::class);
        factory(Product::class)->create(['category_id' => null]);
    }

    /** @test */
    public function it_needs_an_existing_category()
    {
        $this->expectException(QueryException::class);
        factory(Product::class)->create(['category_id' => 9]);
    }

    /** @test */
    public function it_needs_an_existing_product_level_but_it_is_not_required()
    {
        factory(Product::class)->create(['product_level_id' => null]);

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function it_cannot_create_a_product_with_a_game_level_that_does_not_exist()
    {
        $this->expectException(QueryException::class);
        factory(Product::class)->create(['product_level_id' => 9]);
    }

    /** @test */
    public function two_products_cannot_have_the_same_dkt_id()
    {
        $this->expectException(QueryException::class);

        factory(Product::class)->create(['dkt_id' => 1]);
        factory(Product::class)->create(['dkt_id' => 1]);
    }

    /** @test */
    public function two_products_cannot_have_the_same_name()
    {
        $this->expectException(QueryException::class);

        factory(Product::class)->create(['name' => 'Foo']);
        factory(Product::class)->create(['name' => 'Foo']);
    }

    /** @test */
    public function product_name_is_required()
    {
        $this->expectException(QueryException::class);

        factory(Product::class)->create(['name' => null]);
    }

    /** @test */
    public function product_description_is_required()
    {
        $this->expectException(QueryException::class);

        factory(Product::class)->create(['description' => null]);
    }

    /** @test */
    public function product_source_url_is_unique()
    {
        $this->expectException(QueryException::class);

        factory(Product::class)->create(['source' => 'http://foo.test']);
        factory(Product::class)->create(['source' => 'http://foo.test']);
    }
}
