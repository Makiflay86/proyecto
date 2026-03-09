<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMessage extends Model
{
    protected $fillable = ['message', 'date'];
}
