<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Hoyvoy\CrossDatabase\Eloquent\Model;

class Tracing extends Model
{
    use HasFactory;

    public function patient(){
        return $this->belongsTo(Patient::class, 'patient_id', 'idpacientes');
    }

    public function binnacle(){
        return $this->hasMany(Binnacle::class);
    }
}
