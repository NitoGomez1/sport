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
        $brand = create(Brand::class);

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
        $brand = create(Brand::class);
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

    /** @test */
    public function it_returns_201_if_brand_is_successfully_created()
    {
        $this->postJson('api/brands/', factory(Brand::class)->raw())
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_cannot_create_two_brands_with_the_same_name()
    {
        $data = ['name' => 'test'];

        create(Brand::class, $data);
        $this->assertCount(1, Brand::all());

        $this->postJson('api/brands/', ['name' => 'test'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Brand::all());
    }

    /** @test */
    public function it_does_not_show_deleted_brands_when_browse_all()
    {
        $brand = create(Brand::class);
        $brand->delete();

        $this->getJson('api/brands')->assertJsonMissing(['name' => $brand->name]);
        $this->assertDatabaseHas('brands', ['name' => $brand->name]);
    }

    /** @test */
    public function it_does_not_show_deleted_brands_when_show_one()
    {
        $brand = create(Brand::class);
        $brand->delete();

        $this->getJson('api/brands/' . $brand->id)->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('brands', ['name' => $brand->name]);
    }

    /** @test */
    public function it_updates_a_brand()
    {
        $brand = create(Brand::class);

        $this->patchJson('api/brands/' . $brand->id, ['name' => 'Updated'])
            ->assertSuccessful();

        $this->assertEquals('Updated', $brand->fresh()->name);
    }

    /** @test */
    public function it_does_not_update_a_brand_if_there_is_another_with_the_same_name()
    {
        create(Brand::class, ['name' => 'Foo']);
        $brand = create(Brand::class, ['name' => 'Bar']);

        $this->patchJson('api/brands/' . $brand->id, ['name' => 'Foo'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Bar', $brand->fresh()->name);
    }

    /** @test */
    public function it_deletes_a_brand()
    {
        $brand = create(Brand::class);
        $this->deleteJson('api/brands/' . $brand->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $brand->name,
            ]);
    }

    /** @test */
    public function it_returns_a_404_if_tries_to_delete_a_brand_that_does_not_exist()
    {
        $this->deleteJson('api/brands/' . 100)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
