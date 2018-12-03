<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_fails_when_tries_to_register_a_user_with_a_non_valid_email()
    {
        $data = [
            'name'                  => 'foo',
            'email'                 => 'foo',
            'password'              => 'secret',
            'password_confirmation' => 'secret',
        ];

        $response = $this->postJson('register', $data);

        $this->assertEquals($response->getStatusCode(), Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertCount(0, User::all());

        $response->assertJsonFragment(str_replace(':attribute', 'email', [__('validation.email')]));
    }
}
