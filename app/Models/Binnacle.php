<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Binnacle extends Model
{
    use HasFactory;

    protected $fillable = ['tracing_id', 'comment'];

    public function tracing(){
        return $this->belongsTo(Tracing::class);
    }
}
