<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'attention_id',
        'full_name',
        'phone',
        'position',
        'detail',
    ];

    public function attention() {
        return $this->belongsTo(Attention::class);
    }
}
