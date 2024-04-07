<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CustomDrink;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class UpdateCustomDrink
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::guard()->user();

        $drink = CustomDrink::find($args['id']);

        if(is_null($drink)) {
            throw new RuntimeException("Drink not found");
        }

        if(!$drink->user->is($user)) {
            throw new RuntimeException("Unauthorized");
        }

        if(array_key_exists('name', $args['data']) && !empty($args['data']['name']))
        {
            $drink->name = $args['data']['name'];
        }

        if(array_key_exists('default_ml', $args['data']) && !empty($args['data']['default_ml'])) {
            if ($args['data']['default_ml'] < 0) {
                throw new RuntimeException("Default volume must be above 0");
            }
            $drink->default_ml = $args['data']['default_ml'];
        }

        if(array_key_exists('sugar', $args['data']) && !empty($args['data']['sugar'])) {
            if ($args['data']['sugar'] < 0) {
                throw new RuntimeException("Sugar must be above 0");
            }
            $drink->sugar = $args['data']['sugar'];
        }

        if(array_key_exists('caffeine', $args['data']) && !empty($args['data']['caffeine'])) {
            if ($args['data']['caffeine'] < 0) {
                throw new RuntimeException("Caffeine must be above 0");
            }
            $drink->caffeine = $args['data']['caffeine'];
        }

        if(array_key_exists('effectiveness', $args['data']) && !empty($args['data']['effectiveness'])) {
            if ($args['data']['effectiveness'] < 0 || $args['data']['effectiveness'] > 1) {
                throw new RuntimeException("effectiveness must be between 0 and 1");
            }
            $drink->effectiveness = $args['data']['effectiveness'];
        }

        if(array_key_exists('is_favourite', $args['data']) && gettype($args['data']['is_favourite'] == "boolean")) {
            $drink->is_favourite = $args['data']['is_favourite'];
        }

        if(array_key_exists('icon', $args['data'])) {
            $drink->icon = $args['data']['icon'];
        }

        $drink->save();
        return $drink;
    }
}
