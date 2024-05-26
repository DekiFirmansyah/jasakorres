<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\User;
use App\Models\Validation;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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

    public function letterValid()
    {
        $user = Auth::user();

        // Surat yang sudah divalidasi sepenuhnya dan memiliki letter_code pada tabel document
        $fullyValidatedLetters = Letter::whereHas('validations', function($query) {
            $query->whereHas('user.roles', function($roleQuery) {
                $roleQuery->where('name', 'director');
            })->where('is_validated', true);
        })->whereHas('document', function($query) {
            $query->whereNotNull('letter_code');
        })->get();

        return view('validations.letter_valid', compact('fullyValidatedLetters'));
    }
    
    public function validateLetter($id, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);
        
        $letter = Letter::findOrFail($id);
        $user = Auth::user();

        // Validasi surat oleh user yang login dengan catatan
        if ($letter->validators()->where('user_id', $user->id)->exists()) {
            $letter->validators()->updateExistingPivot($user->id, [
                'is_validated' => true,
                'notes' => $request->input('notes')
            ]);
        }

        return redirect()->route('validations.index')->with('success', 'Surat berhasil divalidasi.');
    }
}