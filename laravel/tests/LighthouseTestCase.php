<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class LighthouseTestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            "name" => "test",
            "email" => "test@test.com",
            "password" => Hash::make("test"),
            "gender" => true,
            "height" => 160,
            "weight" => 60,
            "hydration_goal" => 60 * 30,
            "sugar_max" => 40,
            "caffeine_max" => 160,

        ]);

        Auth::login($user);
    }

}