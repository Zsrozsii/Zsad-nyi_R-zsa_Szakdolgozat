<?php

namespace Tests\Lighthouse;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CustomDrink;
use App\Models\Drink;
use App\Models\DrinkLogElement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\LighthouseTestCase;

class DrinkTest extends LighthouseTestCase
{
    public function test_drink_query(): void {
        $drink = Drink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1
        ]);

        $this->graphQL(/** @lang GraphQL */ '
            query($id: ID!) {
                drink(id: $id) {
                    name
                    default_ml
                    caffeine
                    sugar
                    effectiveness
                }
            }
        ',
        ["id" => $drink->id]
        )->assertJson([
            'data' => [
                'drink' => [
                    "name" => "test_drink",
                    "default_ml" => 200,
                    "caffeine" => 10,
                    "sugar" => 10,
                    "effectiveness" => 1
                ],
            ],
        ]);
    }

    public function test_drinks_query(): void {
        Drink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1
        ]);

        $drinks = Drink::all(['name', 'id', 'default_ml', 'caffeine', 'sugar', 'effectiveness']);

        $this->graphQL(/** @lang GraphQL */ '
            query {
                drinks {
                    id
                    name
                    default_ml
                    caffeine
                    sugar
                    effectiveness
                    created_at
                    updated_at
                    icon
                }
            }
        '
        )->assertJson([
            "data" => [
                "drinks" => $drinks->toArray()
            ]
        ]);
    }

    public function test_custom_drinks_query(): void {
        CustomDrink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1,
            "user_id" => Auth::id(),
            "is_favourite" => false
        ]);

        $drinks = CustomDrink::where('user_id', Auth::id())->get(['name', 'id', 'default_ml', 'caffeine', 'sugar', 'effectiveness']);

        $this->graphQL(/** @lang GraphQL */ '
            query {
                customDrinks {
                    id
                    name
                    default_ml
                    caffeine
                    sugar
                    effectiveness
                    created_at
                    updated_at
                    icon
                }
            }
        '
        )->assertJson([
            "data" => [
                "customDrinks" => $drinks->toArray()
            ]
        ]);
    }

    public function test_add_drink_mutation(): void {
        $drink = Drink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 0.98,
        ]);

        $data = [
            "is_custom" => false,
            "id" => $drink->id,
            "volume" => 200
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($data: DrinkInput!) {
                addDrink(data: $data) {
                    drink_name
                    volume
                    sugar
                    caffeine
                }
            }
        ',
        ["data" => $data]
        )->assertJson([
            "data" => [
                "addDrink" => [
                    "drink_name" => "test_drink",
                    "volume"=> 200,
                    "sugar" => 20,
                    "caffeine" => 20
                ]
            ]
        ]);
    }

    public function test_create_custom_drink_mutation(): void {
        $data = [
            "name" => "test_drink",
            "default_ml" => 200,
            "sugar" => 10,
            "caffeine" => 10,
            "effectiveness" => 0.98,
            "icon" => "mdi-cup"
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($data: CreateCustomDrinkInput!) {
                createCustomDrink(data: $data) {
                    name
                    default_ml
                    sugar
                    caffeine
                    effectiveness
                    icon
                }
            }
        ',
        ["data" => $data]
        )->assertJson([
            "data" => [
                "createCustomDrink" => $data
            ]
        ]);
    }

    public function test_update_custom_drink_mutation(): void {

        $drink = CustomDrink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1,
            "user_id" => Auth::id(),
            "is_favourite" => false
        ]);


        $data = [
            "name" => "test_drink1",
            "default_ml" => 300,
            "sugar" => 20,
            "caffeine" => 20,
            "effectiveness" => 0.99,
            "icon" => "mdi-water"
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($data: UpdateCustomDrinkInput! $id: ID!) {
                updateCustomDrink(data: $data id: $id) {
                    name
                    default_ml
                    sugar
                    caffeine
                    effectiveness
                    icon
                }
            }
        ',
        ["data" => $data, "id" => $drink->id]
        )->assertJson([
            "data" => [
                "updateCustomDrink" => $data
            ]
        ]);
    }

    public function test_delete_custom_drink_mutation(): void {
        $drink = CustomDrink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1,
            "user_id" => Auth::id(),
            "is_favourite" => false
        ]);


        $data = [
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 1,
        ];

        $this->graphQL(/** @lang GraphQL */ '
            mutation($id: ID!) {
                deleteCustomDrink(id: $id) {
                    name
                    default_ml
                    sugar
                    caffeine
                    effectiveness
                }
            }
        ',
        ["id" => $drink->id]
        )->assertJson([
            "data" => [
                "deleteCustomDrink" => $data
            ]
        ]);

        $drinkdb = CustomDrink::find($drink->id);

        $this->assertNull($drinkdb);

    }

    public function test_set_favourite_drink_mutation(): void {
        $drink = Drink::create([
            "name" => "test_drink",
            "default_ml" => 200,
            "caffeine" => 10,
            "sugar" => 10,
            "effectiveness" => 0.98,
        ]);

        $this->graphQL(/** @lang GraphQL */ '
            mutation($id: ID! $favourite: Boolean!) {
                setFavouriteDrink(id: $id favourite: $favourite) {
                    id
                    is_favourite
                }
            }
        ',
        ["id" => $drink->id, "favourite" => true]
        )->assertJson([
            "data" => [
                "setFavouriteDrink" => [
                    "id" => $drink->id,
                    "is_favourite" => true
                ]
            ]
        ]);
    }

    public function test_delete_drink_log_mutation(): void {
        $logElement = DrinkLogElement::create([
            "drink_name" => "test_drink",
            "volume"=> 200,
            "sugar" => 20,
            "caffeine" => 20,
            'user_id' => Auth::id()
        ]); 

        $this->graphQL(/** @lang GraphQL */ '
            mutation($id: ID!) {
                deleteDrinkLogElement(id: $id) {
                    drink_name
                    volume
                    sugar
                    caffeine
                }
            }
        ',
        ["id" => $logElement->id]
        )->assertJson([
            "data" => [
                "deleteDrinkLogElement" => [
                    "drink_name" => "test_drink",
                    "volume"=> 200,
                    "sugar" => 20,
                    "caffeine" => 20,
                ]
            ]
        ]);

        $logElementdb = DrinkLogElement::find($logElement->id);

        $this->assertNull($logElementdb);
    }

    public function test_current_levels_query(): void {
        DrinkLogElement::create([
            "drink_name" => "test_drink",
            "volume"=> 200,
            "sugar" => 20,
            "caffeine" => 20,
            'user_id' => Auth::id()
        ]); 

        $user = Auth::user();

        $data = [
            "current"=> 200,
            "goal" => $user->hydration_goal,
            "sugar" => 20,
            "sugar_max" => $user->sugar_max,
            "caffeine" => 20,
            "caffeine_max" => $user->caffeine_max
        ];

        $this->graphQL(/** @lang GraphQL */ '
            query {
                currentLevels {
                    current
                    goal
                    caffeine
                    caffeine_max
                    sugar
                    sugar_max
                }
            }
        ',
        )->assertJson([
            "data" => [
                "currentLevels" => $data
            ]
        ]);
    }

}