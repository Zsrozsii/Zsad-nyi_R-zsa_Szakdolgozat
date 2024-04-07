<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class CurrentLevels
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::guard()->user();

        if(is_null($user)) {
            throw new RuntimeException("User doesn't exist");
        }

        $levels = $user->currentLevels();

        return $levels;
    }
}
