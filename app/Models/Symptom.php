<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'attention_id',
        'cough',
        'throat_pain',
        'nasal_congestion',
        'difficulty_breathing',
        'fever',
        'general_discomfort',
        'diarrhea',
        'nausea_vomiting',
        'headache',
        'irritability',
        'short_breath',
        'anosmia_ausegia',
        'medication_treat',
        'immune_condition',
        'others',
    ];

    protected $casts = [
        'cough' => 'boolean',
        'throat_pain' => 'boolean',
        'nasal_congestion' => 'boolean',
        'difficulty_breathing' => 'boolean',
        'fever' => 'boolean',
        'general_discomfort' => 'boolean',
        'diarrhea' => 'boolean',
        'nausea_vomiting' => 'boolean',
        'headache' => 'boolean',
        'irritability' => 'boolean',
        'short_breath' => 'boolean',
        'anosmia_ausegia' => 'boolean'
    ];

    public function attention(){
        return $this->belongsTo(Attention::class);
    }
}
