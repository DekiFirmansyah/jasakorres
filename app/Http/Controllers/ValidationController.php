<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\User;
use App\Models\Validation;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil surat yang belum divalidasi oleh user saat ini
        $lettersToValidate = Letter::whereHas('validators', function($query) use ($user) {
            $query->where('user_id', $user->id)->where('is_validated', false);
        })->get();
        
        return view('validations.index', compact('lettersToValidate'));
    }

    
    public function validateLetter($id)
    {
        $letter = Letter::findOrFail($id);
        $user = Auth::user();

        // Validasi surat oleh user yang login
        if ($letter->validators()->where('user_id', $user->id)->exists()) {
            $letter->validators()->updateExistingPivot($user->id, ['is_validated' => true]);
        }

        return redirect()->route('validations.index')->with('success', 'Surat berhasil divalidasi.');
    }
}