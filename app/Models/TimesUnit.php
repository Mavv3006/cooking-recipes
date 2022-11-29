<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimesUnit extends Model
{
    use HasFactory;

    protected $fillable = ['short', 'long'];

    public function recipeTimes(): HasMany
    {
        return $this->hasMany(RecipeTimes::class);
    }
}
