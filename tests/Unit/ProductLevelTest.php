<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductLevel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductLevelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_many_products()
    {
        $level = factory(ProductLevel::class)->create();
        factory(Product::class)->make(['product_level_id' => $level->id]);

        $this->assertInstanceOf(HasMany::class, $level->products());
        $this->assertInstanceOf(Collection::class, $level->products);
    }
}
