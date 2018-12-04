<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_all_categories()
    {
        $category = create(Category::class);

        $this->getJson('api/categories')
            ->assertSuccessful()
            ->assertJsonFragment(['name' => $category->name]);
    }

    /** @test */
    public function it_shows_a_category()
    {
        $category = create(Category::class);
        $this->getJson('api/categories/' . $category->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $category->name,
            ]);
    }

    /** @test */
    public function it_returns_404_if_category_does_not_exist()
    {
        $this->getJson('api/categories/' . 10)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_returns_201_if_category_is_successfully_created()
    {
        $this->postJson('api/categories/', factory(Category::class)->raw())
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function it_cannot_create_two_categories_with_the_same_name()
    {
        $data = ['name' => 'test'];

        create(Category::class, $data);
        $this->assertCount(1, Category::all());

        $this->postJson('api/categories/', ['name' => 'test'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function it_does_not_show_deleted_categories_when_browse_all()
    {
        $category = create(Category::class);
        $category->delete();

        $this->getJson('api/categories')->assertJsonMissing(['name' => $category->name]);
        $this->assertDatabaseHas('categories', ['name' => $category->name]);
    }

    /** @test */
    public function it_does_not_show_deleted_categories_when_show_one()
    {
        $category = create(Category::class);
        $category->delete();

        $this->getJson('api/categories/' . $category->id)->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('categories', ['name' => $category->name]);
    }

    /** @test */
    public function it_updates_a_category()
    {
        $category = create(Category::class);

        $this->patchJson('api/categories/' . $category->id, ['name' => 'Updated'])
            ->assertSuccessful();

        $this->assertEquals('Updated', $category->fresh()->name);
    }

    /** @test */
    public function it_does_not_update_a_category_if_there_is_another_with_the_same_name()
    {
        create(Category::class, ['name' => 'Foo']);
        $category = create(Category::class, ['name' => 'Bar']);

        $this->patchJson('api/categories/' . $category->id, ['name' => 'Foo'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Bar', $category->fresh()->name);
    }

    /** @test */
    public function it_deletes_a_category()
    {
        $category = create(Category::class);
        $this->deleteJson('api/categories/' . $category->id)
            ->assertSuccessful()
            ->assertJsonFragment([
                'name' => $category->name,
            ]);
    }

    /** @test */
    public function it_returns_a_404_if_tries_to_delete_a_category_that_does_not_exist()
    {
        $this->deleteJson('api/categories/' . 100)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
