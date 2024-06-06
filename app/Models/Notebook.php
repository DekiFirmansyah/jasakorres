<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notebook extends Model
{
    use HasFactory;
    
    public $table = 'notebooks';

    protected $fillable = [
        'date_sent',
        'destination_name',
        'destination_address',
        'letter_id',
        'description'
    ];

    protected $dates = ['date_sent'];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function getDateSentAttribute($value)
    {
        return Carbon::parse($value);
    }
}