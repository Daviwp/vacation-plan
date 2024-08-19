<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationPlan extends Model
{
    protected $fillable = ['title', 'description', 'date', 'location', 'participants'];

    protected $casts = [
        'participants' => 'array', // Converte automaticamente para e de JSON
    ];
}
