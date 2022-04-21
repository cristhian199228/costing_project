<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Hoyvoy\CrossDatabase\Eloquent\Model;

class Attention extends Model
{
    use HasFactory;

    public function patient(){
        return $this->belongsTo(Patient::class, 'patient_id', 'idpacientes');
    }

    public function earlyTemp(){
        return $this->hasOne(EarlyTemperature::class);
    }

    public function lateTemp(){
        return $this->hasOne(LateTemperature::class);
    }

    public function symptoms(){
        return $this->hasOne(Symptom::class);
    }

    public function epidemiologicalHistories() {
        return $this->hasOne(EpidemiologicalHistory::class);
    }

    public function directContacts() {
        return $this->hasMany(DirectContact::class);
    }
}
