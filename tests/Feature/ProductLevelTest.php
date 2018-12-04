<?php

namespace Tests\Feature;

use App\Models\ProductLevel;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductLevelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_all_product_levels()
    {
        $this->withExceptionHandling();
        $level = factory(ProductLevel::class)->create();

        $this->getJson('api/product-levels')
            ->assertSuccessful()
            ->assertJsonFragment(['name' => $level->name]);
    }

    /** @test */
    public function it_shows_a_product_level()
    {
        $level = factory(ProductLevel::class)->create();
        $this->getJson('api/product-levels/' . $level->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $level->name,
            ]);
    }

    /** @test */
    public function it_returns_404_if_product_level_does_not_exist()
    {
        $this->getJson('api/product-levels/' . 10)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_returns_201_if_product_level_is_successfully_created()
    {
        $this->postJson('api/product-levels/', factory(ProductLevel::class)->raw())
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_cannot_create_two_product_levels_with_the_same_name()
    {
        $data = ['name' => 'test'];

        factory(ProductLevel::class)->create($data);
        $this->assertCount(1, ProductLevel::all());

        $this->postJson('api/product-levels/', ['name' => 'test'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, ProductLevel::all());
    }

    /** @test */
    public function it_does_not_show_deleted_product_levels_when_browse_all()
    {
        $level = factory(ProductLevel::class)->create();
        $level->delete();

        $this->getJson('api/product-levels')->assertJsonMissing(['name' => $level->name]);
        $this->assertDatabaseHas('product_levels', ['name' => $level->name]);
    }

    /** @test */
    public function it_does_not_show_deleted_product_levels_when_show_one()
    {
        $level = factory(ProductLevel::class)->create();
        $level->delete();

        $this->getJson('api/product-levels/' . $level->id)->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('product_levels', ['name' => $level->name]);
    }

    /** @test */
    public function it_updates_a_product_level()
    {
        $level = factory(ProductLevel::class)->create();

        $this->patchJson('api/product-levels/' . $level->id, ['name' => 'Updated'])
            ->assertSuccessful();

        $this->assertEquals('Updated', $level->fresh()->name);
    }

    /** @test */
    public function it_does_not_update_a_product_level_if_there_is_another_with_the_same_name()
    {
        factory(ProductLevel::class)->create(['name' => 'Foo']);
        $level = factory(ProductLevel::class)->create(['name' => 'Bar']);

        $this->patchJson('api/product-levels/' . $level->id, ['name' => 'Foo'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Bar', $level->fresh()->name);
    }

    /** @test */
    public function it_deletes_a_product_level()
    {
        $level = factory(ProductLevel::class)->create();
        $this->deleteJson('api/product-levels/' . $level->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $level->name,
            ]);
    }

    /** @test */
    public function it_returns_a_404_if_tries_to_delete_a_category_that_does_not_exist()
    {
        $this->deleteJson('api/product-levels/' . 100)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
