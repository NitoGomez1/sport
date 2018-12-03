<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_name_must_be_filled()
    {
        $this->expectException(QueryException::class);

        factory(User::class)->create(['name' => null]);
    }

    /** @test */
    public function user_email_must_be_filled()
    {
        $this->expectException(QueryException::class);

        factory(User::class)->create(['email' => null]);
    }
}
