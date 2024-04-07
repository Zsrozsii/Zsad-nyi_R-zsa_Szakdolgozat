<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use RuntimeException;

final class ChangeSettings
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::guard()->user();

        if (array_key_exists('name', $args['data']) && !empty($args['data']['name'])) {
            if (strlen($args['data']['name']) < 3 ) {
                throw new RuntimeException("Name should contain at least 3 characters");
            }
            $user->name = $args['data']['name'];
        }
        if (array_key_exists('email', $args['data']) && !empty($args['data']['email'])) {
            $userCheck = User::where('email', $args['data']['email'])->first();

            if(!is_null($userCheck) && !$userCheck->is($user) ) {
                throw new RuntimeException("Email already exists");
            }

            $user->email = $args['data']['email'];
        }

        if (array_key_exists('height', $args['data']) && !empty($args['data']['height'])) {
            if($args['data']['height'] < 100 || $args['data']['height'] > 250) {
                throw new RuntimeException("Height should be between 100 and 250 cm");
            }
            $user->height = $args['data']['height'];
        }

        if (array_key_exists('weight', $args['data']) && !empty($args['data']['weight'])) {
            if($args['data']['weight'] < 30 || $args['data']['weight'] > 200) {
                throw new RuntimeException('Weight should be between 30 and 200 Kg');
            }
            $user->weight = $args['data']['weight'];
        }
        if (array_key_exists('hydration_goal', $args['data']) && !empty($args['data']['hydration_goal'])) {
            if($args['data']['hydration_goal'] < 0) {
                throw new RuntimeException("Hydration goal should be above 0");
            }
            $user->hydration_goal = $args['data']['hydration_goal'];
        }

        if (array_key_exists('caffeine_max', $args['data']) && !empty($args['data']['caffeine_max'])) {
            if($args['data']['caffeine_max'] < 0) {
                throw new RuntimeException("caffeine maximum should be above 0");
            }
            $user->caffeine_max = $args['data']['caffeine_max'];
        }

        if (array_key_exists('sugar_max', $args['data']) && !empty($args['data']['sugar_max'])) {
            if($args['data']['sugar_max'] < 0) {
                throw new RuntimeException("sugar maximum should be above 0");
            }
            $user->sugar_max = $args['data']['sugar_max'];
        }

        $user->save();
        return $user;
    }
}
