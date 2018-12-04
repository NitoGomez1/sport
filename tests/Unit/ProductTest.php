<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLevel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
