<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getTokenLogin()
    {
        $user = User::factory()->create();

        return auth()->claims([
            "exp" => $exp ?? strtotime('now +' . env('JWT_TOKEN_MINUTES', 60) . ' minutes'),
        ])->login($user);
    }
}
