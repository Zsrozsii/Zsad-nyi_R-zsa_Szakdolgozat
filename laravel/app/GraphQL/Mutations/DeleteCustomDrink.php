<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CustomDrink;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class DeleteCustomDrink
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

        $drink->delete();

        return $drink;
    }
}
