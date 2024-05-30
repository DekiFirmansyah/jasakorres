<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\User;
use App\Models\Validation;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ValidationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('name')->first(); // Mendapatkan role pertama user

        // Ambil surat yang belum divalidasi oleh user saat ini
        $lettersToValidate = Letter::whereHas('validators', function($query) use ($user, $role) {
            $query->where('user_id', $user->id)->where('is_validated', false);
        })->where(function($query) use ($role) {
            if ($role == 'manager') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'secretary');
                    })->where('is_validated', true);
                });
            } elseif ($role == 'director') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'manager' dan 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->whereIn('name', ['manager', 'secretary']);
                    })->where('is_validated', true);
                });
            }
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
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        
        $letter = Letter::findOrFail($id);
        $user = Auth::user();

        // Default validation status is false
        // $isValidated = false;

        // Get the associated file path
        $filePath = $letter->document->file ?? null;

        // Handle file upload if a new document is uploaded
        if ($file = $request->file('file')) {
            // Delete the old file if it exists
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            // Store the new file
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('document', $file, $fileName);
        }

        // Update the associated Document record
        $letter->document->update([
            'file' => $filePath,
        ]);
        
        // If notes or file are provided, send notification to the letter creator
        if ($request->input('notes') || $file) {
            $creator = $letter->user;
            if ($creator) {
                $creator->notify(new LetterValidationNotification($letter, $request->input('notes')));
            }
        } else {
            // Validasi surat oleh user yang login dengan catatan
            if ($letter->validators()->where('user_id', $user->id)->exists()) {
                $letter->validators()->updateExistingPivot($user->id, [
                    'is_validated' => true,
                    'notes' => $request->input('notes')
                ]);
                // $isValidated = true;
            }
        }

        // Kirim notifikasi ke validator selanjutnya jika notes dan file kosong
        if (!$request->input('notes') && !$file) {
            $nextValidatorId = null;

            // Cek role user saat ini dan tentukan validator selanjutnya berdasarkan user_id di tabel validations
            if ($user->hasRole('secretary')) {
                // Sekretaris validasi -> Notifikasi ke manager
                $nextValidator = $letter->validators()->whereHas('user.roles', function($query) {
                    $query->where('name', 'manager');
                })->first();
            } elseif ($user->hasRole('manager')) {
                // Manager validasi -> Notifikasi ke director
                $nextValidator = $letter->validators()->whereHas('user.roles', function($query) {
                    $query->where('name', 'director');
                })->first();
            }

            // Jika ada validator selanjutnya, cek apakah surat sudah divalidasi oleh semua validator sebelumnya
            if ($nextValidator) {
                $nextValidatorId = $nextValidator->user_id;

                $allPreviousValidated = $letter->validators()->whereHas('user.roles', function($query) use ($user) {
                    if ($user->hasRole('manager')) {
                        $query->where('name', 'secretary');
                    } elseif ($user->hasRole('director')) {
                        $query->whereIn('name', ['manager', 'secretary']);
                    }
                })->where('is_validated', true)->count() > 0;

                if ($allPreviousValidated && $nextValidatorId) {
                    $nextValidatorUser = User::find($nextValidatorId);
                    if ($nextValidatorUser) {
                        $nextValidatorUser->notify(new LetterValidationNotification($letter));
                    }
                }
            }
        }

        return redirect()->route('validations.index')->with('success', 'Surat berhasil divalidasi.');
    }
}