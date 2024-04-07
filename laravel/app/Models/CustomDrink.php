<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDrink extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'default_ml',
        'sugar',
        'caffeine',
        'effectiveness',
        'user_id',
        'is_favourite',
        'icon'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
