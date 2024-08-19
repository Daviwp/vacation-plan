<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationPlan extends Model
{
    protected $fillable = ['title', 'description', 'date', 'location', 'participants'];
}
