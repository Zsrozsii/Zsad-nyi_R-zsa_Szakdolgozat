<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'weight',
        'height',
        'hydration_goal',
        'caffeine_max',
        'sugar_max'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function currentLevels()
    {
        $drink_elements = $this->drinkLogElements()->whereDate('created_at', Carbon::today())->get();

        $hydr = 0;
        $caff = 0;
        $sug = 0;

        foreach ($drink_elements as $element)
        {
            $hydr += $element->volume * $element->effectiveness;
            $caff += $element->caffeine;
            $sug += $element->sugar;
        }

        $data = [
            "current" => $hydr,
            "caffeine" => $caff,
            "sugar" => $sug,
            "goal" => $this->hydration_goal,
            "caffeine_max" => $this->caffeine_max,
            "sugar_max" => $this->sugar_max,
        ];

        $score = $this->calculateScore($data);
        $data['score'] = $score;

        return $data;
    }

    private function calculateScore($data): int {
        $score = 0;

        if($data['current'] >= $data['goal'] / 2 ) {
            $score = $score + 1;
        }
        if($data['current'] > $data['goal'] ) {
            $score = $score + 1;
        }

        if($data['sugar'] <= $data['sugar_max'] * 2) {
            $score = $score + 1;
        }
        if($data['sugar'] <= $data['sugar_max']) {
            $score = $score + 1;
        }

        if($data['caffeine'] <= $data['caffeine_max'] * 2) {
            $score = $score + 1;
        }
        if($data['caffeine'] <= $data['caffeine_max']) {
            $score = $score + 1;
        }

        return $score;
    }

    public function drinkLogElements(): HasMany
    {
        return $this->hasMany(DrinkLogElement::class);
    }

    public function customDrinks(): HasMany
    {
        return $this->hasMany(CustomDrink::class);
    }

    public function favouriteDrinks(): BelongsToMany
    {
        return $this->belongsToMany(Drink::class, 'user_favourite_drinks');
    }
}
