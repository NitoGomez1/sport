<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_all_brands()
    {
        $brand = factory(Brand::class)->create();
        $this->getJson('api/brands')
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $brand->name,
            ]);

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function it_shows_a_brand()
    {
        $brand = factory(Brand::class)->create();
        $this->getJson('api/brands/' . $brand->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $brand->name,
            ]);
    }

    /** @test */
    public function it_returns_404_if_brand_does_not_exist()
    {
        $this->getJson('api/brands/' . 10)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
