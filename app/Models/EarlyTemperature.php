<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyTemperature extends Model
{
    use HasFactory;

    protected $fillable = [
        'attention_id',
        'value'
    ];

    public function attention(){
        return $this->belongsTo(Attention::class);
    }
}
