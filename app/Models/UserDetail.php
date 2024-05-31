<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    public $table = 'user_details';

    protected $fillable = [
        'nip',
        'jabatan',
        'foto',
        'description',
        'user_id',
        'division_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}