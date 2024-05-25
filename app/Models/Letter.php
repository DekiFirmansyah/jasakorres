<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;

class Letter extends Model
{
    use HasFactory;

    public $table = 'letters';

    protected $fillable = [
        'title',
        'about',
        'purpose',
        'user_id',
        'document_id',
        'description'
    ];

    public function document()
    {
    	return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validations()
    {
        return $this->hasMany(Validation::class);
    }

    public function validators()
    {
        return $this->belongsToMany(User::class, 'validations', 'letter_id', 'user_id')
                    ->withPivot('is_validated', 'notes');
    }
}