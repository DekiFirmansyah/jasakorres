<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Validation extends Model
{
    use HasFactory, Notifiable;

    public $table = 'validations';

    protected $fillable = [
        'letter_id',
        'user_id',
        'is_validated',
        'notes',
        'revised_file'
    ];
    
    public function letter()
    {
    	return $this->belongsTo(Letter::class);
    }
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}