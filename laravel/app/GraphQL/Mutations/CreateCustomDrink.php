<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CustomDrink;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class CreateCustomDrink
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::guard()->user();

        if($args['data']['default_ml'] < 0) {
            throw new RuntimeException("Default volume must be above 0");
        }

        if($args['data']['sugar'] < 0) {
            throw new RuntimeException("Sugar must be above 0");
        }

        if($args['data']['caffeine'] < 0) {
            throw new RuntimeException("Caffeine must be above 0");
        }

        if($args['data']['effectiveness'] < 0 || $args['data']['effectiveness'] > 1) {
            throw new RuntimeException("effectiveness must be between 0 and 1");
        }

        $drink = CustomDrink::create([
            'name'=> $args['data']['name'],
            'default_ml' => $args['data']['default_ml'],
            'sugar' => $args['data']['sugar'],
            'caffeine' => $args['data']['caffeine'],
            'effectiveness' => $args['data']['effectiveness'],
            'user_id' => $user->id,
            'is_favourite' => false,
            'icon' => $args['data']['icon'],
        ]);

        return $drink;
    }
}
