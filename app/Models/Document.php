<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Document extends Model
{
    use HasFactory, Notifiable;

    public $table = 'documents';

    protected $fillable = [
        'letter_code',
        'file'
    ];

    public function letter()
    {
    	return $this->hasOne(Letter::class);
    }

}