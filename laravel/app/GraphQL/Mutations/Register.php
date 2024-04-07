<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Models\CustomDrink;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Illuminate\Support\Facades\Hash;

final class Register
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::find($args['data']['email']);

        if ($user){
            throw ValidationException::withMessages([
                "email"=> "User already exists"
            ]);
        }

        $user = User::create([
            "name" => $args['data']['name'],
            "email" => $args['data']['email'],
            "password" => Hash::make($args['data']['password']),
            "gender" => $args['data']['gender'],
            "height" => $args['data']['height'],
            "weight" => $args['data']['weight'],
            "hydration_goal" => $args['data']['weight'] * 30,
            "sugar_max" => 40,
            "caffeine_max" => 160,
        ]);

        
        if(is_null($user)) {
            return  false;
        }

        $drink = CustomDrink::create([
            'name'=> "Coffee",
            'default_ml' => 40,
            'sugar' => 7,
            'caffeine' => 80,
            'effectiveness' => 0.98,
            'user_id' => $user->id,
            'is_favourite' => true,
        ]);

        $drink = CustomDrink::create([
            'name'=> "Green tea",
            'default_ml' => 150,
            'sugar' => 7,
            'caffeine' => 40,
            'effectiveness' => 0.98,
            'user_id' => $user->id,
            'is_favourite' => true,
        ]);

        $drink = CustomDrink::create([
            'name'=> "Coke",
            'default_ml' => 200,
            'sugar' => 14,
            'caffeine' => 20,
            'effectiveness' => 0.98,
            'user_id' => $user->id,
            'is_favourite' => true,
        ]);

        return true;
    }
}
