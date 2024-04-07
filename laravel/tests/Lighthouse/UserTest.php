<?php

namespace Tests\Lighthouse;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\LighthouseTestCase;

class UserTest extends LighthouseTestCase
{
    public function test_user_query(): void
    {
        $user = new User([
            "name" => "test1",
            "email" => "test1@test.com",
            "password" => Hash::make("test"),
            "gender" => true,
            "height" => 160,
            "weight" => 60,
            "hydration_goal" => 60 * 30,
            "sugar_max" => 40,
            "caffeine_max" => 160,

        ]);

        $user->save();

        $this->graphQL(/** @lang GraphQL */ '
            query($id: ID) {
                user(id: $id) {
                    name
                    email
                    gender
                    height
                    weight
                    hydration_goal
                    sugar_max
                    caffeine_max
                }
            }
        ',
        ["id" => $user->id]
        )->assertJson([
            'data' => [
                'user' => [
                    "name" => "test1",
                    "email" => "test1@test.com",
                    "gender" => true,
                    "height" => 160,
                    "weight" => 60,
                    "hydration_goal" => 60 * 30,
                    "sugar_max" => 40,
                    "caffeine_max" => 160,
                ],
            ],
        ]);

    }

    public function test_me_query() {
        $user = Auth::user();


        $this->graphQL(/** @lang GraphQL */ '
            {
                me {
                    name
                    email
                    height
                    weight
                    hydration_goal
                    sugar_max
                    caffeine_max
                }
            }
        '
        )->assertJson([
            'data' => [
                'me' => [
                    "name" => $user->name,
                    "email" => $user->email,
                    "height" => $user->height,
                    "weight" => $user->weight,
                    "hydration_goal" => $user->hydration_goal,
                    "sugar_max" => $user->sugar_max,
                    "caffeine_max" => $user->caffeine_max,
                ],
            ],
        ]);
    }

    public function test_register() {
        $data = [
            "name" => "test2",
            "email" => "test2@test.com",
            "password" => "test",
            "gender" => true,
            "height" => 160,
            "weight" => 60,
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($data: RegisterInput!) {
                register(data: $data) 
            }
        ',
        ["data" => $data]
        )->assertJson([
            'data' => true
        ]);


        $user = User::where("email", $data['email'])->first();

        $dataCheck = [
            "name" => "test2",
            "email" => "test2@test.com",
            "gender" => true,
            "height" => 160,
            "weight" => 60,
            "hydration_goal" => 60 * 30,
            "sugar_max" => 40,
            "caffeine_max" => 160,
        ];
        $userData = [
            "name" => $user->name,
            "email" => $user->email,
            "gender" => $user->gender,
            "height" => $user->height,
            "weight" => $user->weight,
            "hydration_goal" => $user->hydration_goal,
            "sugar_max" => $user->sugar_max,
            "caffeine_max" => $user->caffeine_max,
        ];
        $this->assertEquals($dataCheck, $userData);
    }

    function test_change_settings_mutation(): void {
        $data = [
            "name" => "test_2",
            "email" => "test2@test.com",
            "height" => 180,
            "weight" => 74,
            "hydration_goal" => 2120,
            "caffeine_max" => 170,
            "sugar_max" => 37,
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($data: UserDataInput!) {
                changeSettings(data: $data) {
                    name
                    email
                    height
                    weight
                    hydration_goal
                    caffeine_max
                    sugar_max
                }
            }
        ',
        ["data" => $data]
        )->assertJson([
            "data" => [
                "changeSettings" => $data
            ]
        ]);
    }

}
