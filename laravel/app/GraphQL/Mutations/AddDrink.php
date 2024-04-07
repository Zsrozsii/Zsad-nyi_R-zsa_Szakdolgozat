<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\DrinkLogElement;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class AddDrink
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        if (!$args['data']['is_custom'])
        {
            $drink = \App\Models\Drink::find($args['data']['id']);
        }
        else {
            $drink = \App\Models\CustomDrink::find($args['data']['id']);
        }

        if(is_null($drink)) {
            throw new RuntimeException("Drink not found");
        }

        $rate = $args['data']['volume'] / 100;
        $caff = $drink->caffeine ? $drink->caffeine * $rate : 0;
        $sugar = $drink->sugar ? $drink->sugar * $rate : 0;

        $user = Auth::guard()->user();

        $drinkLog = new DrinkLogElement([
            "user_id" => $user->id,
            "volume" => $args['data']['volume'],
            "sugar" => $sugar,
            "caffeine" => $caff,
            "drink_name" => $drink->name,
            "effectiveness" => $drink->effectiveness
        ]);
        

        $drinkLog->save();

        return $drinkLog;
    }
}
