<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpidemiologicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'attention_id',
        'traveled_14days_ago',
        'places_visited',
        'conveyance',
        'arrival_date',
        'close_contact',
        'covid_conversation',
        'last_contact_date',
    ];

    protected $casts = [
        'traveled_14days_ago' => 'boolean',
        'close_contact' => 'boolean',
        'covid_conversation' => 'boolean',
    ];

    public function attention(){
        return $this->belongsTo(Attention::class);
    }
}
