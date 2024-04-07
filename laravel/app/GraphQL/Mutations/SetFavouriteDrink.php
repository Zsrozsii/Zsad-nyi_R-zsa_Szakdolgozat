<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Models\Drink;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class SetFavouriteDrink
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::guard()->user();

        $drink = Drink::find($args['id']);

        if(is_null($drink)) {
            throw new RuntimeException("Drink not found");
        }

        if($args['favourite']) {
            $drink->users()->attach($user);
        }
        else {
            $drink->users()->detach($user);
        }
        

        return $drink;
    }
}
