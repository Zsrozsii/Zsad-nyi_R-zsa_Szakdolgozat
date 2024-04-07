<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Drink extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'default_ml', 'caffeine', 'sugar', 'effectiveness', 'icon'];

    public function getIsFavouriteAttribute(): bool
    {
        $user = Auth::guard()->user();

        if(is_null($user)) {
            return false;
        }

        if($this->users()->get()->contains($user))
        {
            return true;
        }
        else 
        {
            return false;
        }

    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favourite_drinks');
    }
}
