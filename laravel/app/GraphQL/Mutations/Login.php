<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Exceptions\ValidationException;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::where('email', $args['email'])->first();

//        || Hash::check($args['password'], $user->password)

        if (!$user || !Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                "email" => "incorrect email or password"
            ]);
        }

        $token = $user->createToken("web")->plainTextToken;

        return $token;
    }
}
