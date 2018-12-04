<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLevel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_many_products()
    {
        $brand = factory(Brand::class)->create();
        factory(Product::class)->make(['brand_id' => $brand->id]);

        $this->assertInstanceOf(HasMany::class, $brand->products());
        $this->assertInstanceOf(Collection::class, $brand->products);
    }
}
