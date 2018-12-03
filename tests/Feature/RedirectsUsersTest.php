<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectsUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_home_when_user_logs_in()
    {
        $this->be(factory(User::class)->create());

        $this->get('home')
            ->assertSuccessful()
            ->assertSee('You are logged in!');
    }

    /** @test */
    public function it_redirects_to_login_if_you_visit_a_protected_page_and_you_are_not_authenticated()
    {
        $this->get('home')
            ->assertRedirect('login');
    }
}
