<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_many_products()
    {
        $category = create(Category::class);
        factory(Product::class)->make(['category_id' => $category->id]);

        $this->assertInstanceOf(HasMany::class, $category->products());
        $this->assertInstanceOf(Collection::class, $category->products);
    }
}
