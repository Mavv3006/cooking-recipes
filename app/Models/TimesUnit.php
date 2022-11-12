<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesUnit extends Model
{
    use HasFactory;

    protected $fillable = ['short', 'long'];
}
