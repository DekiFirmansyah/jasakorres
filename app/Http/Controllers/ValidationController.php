<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\User;
use App\Models\Validation;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\LetterValidationNotification;
use App\Notifications\UpdateLetterNotification;
use App\Notifications\RequestLetterCodeNotification;
use Illuminate\Database\Eloquent\Builder;

class ValidationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('name')->first(); // Mendapatkan role pertama user

        // Ambil surat yang belum divalidasi oleh user saat ini
        $lettersToValidate = Letter::whereHas('validators', function($query) use ($user) {
            $query->where('user_id', $user->id)->where('is_validated', false);
        })->where(function($query) use ($role) {
            if ($role == 'manager') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'secretary');
                    })->where('is_validated', true);
                });
            } elseif ($role == 'general-manager') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'manager' dan 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->whereIn('name', ['manager', 'secretary']);
                    })->where('is_validated', true);
                });
            } elseif ($role == 'general-director') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'general-manager', 'manager' dan 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->whereIn('name', ['general-manager', 'manager', 'secretary']);
                    })->where('is_validated', true);
                });
            } elseif ($role == 'executive-director') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'general-manager', 'manager' dan 'secretary'
                $query->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->whereIn('name', ['general-director', 'general-manager', 'manager', 'secretary']);
                    })->where('is_validated', true);
                });
            }
        })->get();

        
        // Cek apakah user adalah secretary dan apakah surat sudah divalidasi oleh semua validator
        $requestLetterCode = Letter::whereHas('document', function($query) {
            $query->whereNull('letter_code');
        })->whereDoesntHave('validators', function ($query) {
            $query->where('is_validated', false); // Hanya ambil surat yang semua validatornya sudah memvalidasi
        })->get();

        return view('validations.index', compact('lettersToValidate', 'requestLetterCode'));
    }

    public function updateCode(Request $request, $documentId)
    {
        $document = Document::findOrFail($documentId);
        $request->validate([
            'letter_code' => 'required|string|max:255',
        ]);

        $document->letter_code = $request->input('letter_code');
        $document->save();

        if ($document->letter) {
            $document->letter->updated_at = now();
            $document->letter->save();
        }

        return redirect()->route('validations.index')->with('status', 'Kode surat berhasil diupdate.');
    }

    public function letterValid()
    {
        $user = Auth::user();

        // Surat yang sudah divalidasi sepenuhnya dan memiliki letter_code pada tabel document
         $fullyValidatedLetters = Letter::whereHas('validations', function($query) {
            $query->whereHas('user.roles', function($roleQuery) {
                $roleQuery->whereIn('name', ['general-manager', 'general-director', 'executive-director']);
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
            $letter->validators()->updateExistingPivot($user->id, [
                'notes' => $request->input('notes')
            ]);
            
            $creator = $letter->user;
            if ($creator) {
                $creator->notify(new UpdateLetterNotification($letter));
            }
            return redirect()->route('validations.index')
            ->with('status', 'Surat berhasil diperbarui dan notifikasi dikirim ke pembuat surat.');
        } else {
            // Validasi surat oleh user yang login dengan catatan
            if ($letter->validators()->where('user_id', $user->id)->exists()) {
                $letter->validators()->updateExistingPivot($user->id, [
                    'is_validated' => true,
                    'notes' => $request->input('notes')
                ]);
            }
        }

        if (!$request->input('notes') && !$file) {
            $nextValidator = null;

            if ($user->hasRole('secretary')) {
                // Sekretaris validasi -> Notifikasi ke manager atau role yang lebih tinggi jika tidak ada manager
                $nextValidator = $this->getNextValidator($letter, ['manager', 'general-manager', 'general-director', 'executive-director']);
            } elseif ($user->hasRole('manager')) {
                // Manager validasi -> Notifikasi ke general-manager atau role yang lebih tinggi jika tidak ada general-manager
                $nextValidator = $this->getNextValidator($letter, ['general-manager', 'general-director', 'executive-director']);
            } elseif ($user->hasRole('general-manager')) {
                // General-manager validasi -> Notifikasi ke general-director atau executive-director jika tidak ada general-director
                $nextValidator = $this->getNextValidator($letter, ['general-director', 'executive-director']);
            } elseif ($user->hasRole('general-director')) {
                // General-director validasi -> Notifikasi ke executive-director
                $nextValidator = $this->getNextValidator($letter, ['executive-director']);
            }

            if ($nextValidator) {
                $nextValidatorId = $nextValidator->user_id;
            
                $roleHierarchy = [
                    'executive-director' => ['general-director', 'general-manager', 'manager', 'secretary'],
                    'general-director' => ['general-manager', 'manager', 'secretary'],
                    'general-manager' => ['manager', 'secretary'],
                    'manager' => ['secretary'],
                ];
            
                $currentUserRole = $user->roles->first()->name;
                $previousRoles = $roleHierarchy[$currentUserRole] ?? [];
            
                $allPreviousValidated = $letter->validations()
                    ->whereHas('user.roles', function ($query) use ($previousRoles) {
                        $query->whereIn('name', $previousRoles);
                    })
                    ->where('is_validated', true)
                    ->count() == count($previousRoles);
            
                if ($allPreviousValidated && $nextValidatorId) {
                    $nextValidatorUser = User::find($nextValidatorId);
                    if ($nextValidatorUser) {
                        $nextValidatorUser->notify(new LetterValidationNotification($letter));
                    }
                }
            } else {
                // No next validator, notify the secretary to request a letter code
                $secretary = User::whereHas('roles', function ($query) {
                    $query->where('name', 'secretary');
                })->first();
            
                if ($secretary) {
                    $secretary->notify(new RequestLetterCodeNotification($letter));
                }
            }
        }

        return redirect()->route('validations.index')->with('status', 'Surat berhasil divalidasi.');
    }
    
    private function getNextValidator($letter, $roles)
    {
        foreach ($roles as $role) {
            $nextValidator = $letter->validations()->whereHas('user.roles', function($query) use ($role) {
                $query->where('name', $role);
            })->first();

            if ($nextValidator) {
                return $nextValidator;
            }
        }

        return null;
    }
}