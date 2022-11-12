<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeTimes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['recipe_id', 'times_id', 'times_unit_id', 'duration'];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function time(): BelongsTo
    {
        return $this->belongsTo(Times::class, 'times_id', 'id');
    }

    public function timesUnit(): BelongsTo
    {
        return $this->belongsTo(TimesUnit::class);
    }
}
