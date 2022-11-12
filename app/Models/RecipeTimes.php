<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeTimes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['recipe_id', 'times_id', 'times_unit_id', 'duration'];
}
