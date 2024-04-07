<?php

namespace App\Models;

use Cassandra\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrinkLogElement extends Model
{
    use HasFactory;

    protected $table = 'user_drink';
    protected $fillable = ["user_id", "volume", "sugar", "caffeine", "drink_id", "custom_drink_id", "drink_name", "effectiveness"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class);
    }

    public function customDrink(): BelongsTo
    {
        return $this->belongsTo(CustomDrink::class);
    }
}



