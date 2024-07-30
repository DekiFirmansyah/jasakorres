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
                        $q2->where('name', 'manager');
                    })->where('is_validated', true);
                })->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'secretary');
                    })->where('is_validated', true);
                });
            } elseif ($role == 'general-director') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'general-manager', 'manager' dan 'secretary'
                $query->where(function($subQuery) {
                    $subQuery->whereHas('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-manager');
                        })->where('is_validated', true);
                    })->orWhereDoesntHave('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-manager');
                        });
                    });
                })->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'manager');
                    })->where('is_validated', true);
                })->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'secretary');
                    })->where('is_validated', true);
                });
            } elseif ($role == 'executive-director') {
                // Hanya tampilkan surat yang sudah divalidasi oleh 'general-director', 'general-manager', 'manager' dan 'secretary'
                $query->where(function($subQuery) {
                    $subQuery->whereHas('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-director');
                        })->where('is_validated', true);
                    })->orWhereDoesntHave('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-director');
                        });
                    });
                })->where(function($subQuery) {
                    $subQuery->whereHas('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-manager');
                        })->where('is_validated', true);
                    })->orWhereDoesntHave('validations', function($q) {
                        $q->whereHas('user.roles', function($q2) {
                            $q2->where('name', 'general-manager');
                        });
                    });
                })->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'manager');
                    })->where('is_validated', true);
                })->whereHas('validations', function($q) {
                    $q->whereHas('user.roles', function($q2) {
                        $q2->where('name', 'secretary');
                    })->where('is_validated', true);
                });
            }
        })->get();

        $requestLetterCode = Letter::whereHas('document', function($query) {
            $query->whereNull('letter_code');
        })->whereDoesntHave('validators', function ($query) {
            $query->where('is_validated', false);
        })->get();

        return view('validations.index', compact('lettersToValidate', 'requestLetterCode'));
    }

    public function show($id)
    {
        // Ambil data surat berdasarkan ID
        $letter = Letter::with(['validators', 'document'])->findOrFail($id);

        // Ambil riwayat catatan dari tabel validations
        $historyNotes = Validation::where('letter_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view dengan data
        return view('validations.detail', compact('letter', 'historyNotes'));
    }

    public function detailApplyCode($id)
    {
        $letter = Letter::with('document')->findOrFail($id);
        
        return view('validations.detail_code', compact('letter'));
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

        return redirect()->route('validations.index')->with('status', 'Kode surat berhasil diperbarui.');
    }

    public function letterValid(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $query = Letter::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('about', 'like', '%' . $search . '%')
                ->orWhere('purpose', 'like', '%' . $search . '%');
            });
        }

        if ($month) {
            $query->whereMonth('updated_at', date('m', strtotime($month)))
                ->whereYear('updated_at', date('Y', strtotime($month)));
        }
        
        $user = Auth::user();

        $fullyValidatedLetters = $query->whereHas('validations', function($q) use ($user) {
            $q->where('user_id', $user->id)->where('is_validated', true);
        })->whereHas('document', function($q) {
            $q->whereNotNull('letter_code');
        })->paginate(9);

        return view('validations.letter_valid', compact('fullyValidatedLetters', 'search', 'month'));
    }
    
    public function revisionLetter($id, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:doc,docx|max:5000',
        ]);
        
        $letter = Letter::findOrFail($id);
        $user = Auth::user();

        $revisedFilePath = null;
        
        if ($revisedFile = $request->file('file')) {
            $title = $letter->title;
            
            // Membersihkan title dari karakter yang tidak diinginkan
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-_ ]/', '', $title);
            $extension = $revisedFile->getClientOriginalExtension();

            // Tentukan jalur direktori untuk penyimpanan
            $directory = 'document';

            // Menghitung jumlah revisi yang ada
            $files = Storage::disk('public')->files($directory);
            $revisionCount = 0;

            // Cari file dengan nama yang mirip dan hitung jumlah revisi
            foreach ($files as $file) {
                if (strpos($file, $sanitizedTitle . '_revisi') !== false) {
                    $revisionCount++;
                }
            }

            // Tambahkan angka urutan ke nama file
            $revisionNumber = $revisionCount + 1;
            $fileName = $sanitizedTitle . '_revisi_' . str_pad($revisionNumber, 2, '0', STR_PAD_LEFT) . '.' . $extension;

            // Menyimpan file dengan nama baru
            $revisedFilePath = Storage::disk('public')->putFileAs($directory, $revisedFile, $fileName);
        }
        
        // Hanya proses notifikasi jika ada notes atau file yang diunggah
        if ($request->input('notes') || $file) {
            
            $validations = Validation::where('letter_id', $letter->id)
                ->where('user_id', $user->id)
                ->get();
                
            // Ambil entri pertama dari koleksi
            $firstValidation = $validations->first();
            
            if (is_null($firstValidation->notes)) {
                // Jika catatan dari entri pertama adalah null, lakukan update
                $firstValidation->update([
                    'notes' => $request->input('notes'),
                    'revised_file' => $revisedFilePath,
                ]);
            } else {
                // Jika catatan dari entri pertama tidak null, buat entri baru
                Validation::create([
                    'letter_id' => $letter->id,
                    'user_id' => $user->id,
                    'is_validated' => false,
                    'notes' => $request->input('notes'),
                    'revised_file' => $revisedFilePath
                ]);
            }
            
            // Kirim notifikasi kepada pembuat surat
            $creator = $letter->user;
            if ($creator) {
                $creator->notify(new UpdateLetterNotification($letter));
            }
            
            return redirect()->route('validations.index')
                ->with('status', 'Surat berhasil diperbarui dan notifikasi dikirim ke pembuat surat.');
        }
    }

    public function validateSuccess($id, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $letter = Letter::findOrFail($id);
        $user = Auth::user();

        // Periksa apakah pengguna saat ini adalah validator
        if ($letter->validators()->where('user_id', $user->id)->exists()) {
            
            $validations = Validation::where('letter_id', $letter->id)
                ->where('user_id', $user->id)
                ->get();
                
            // Ambil entri pertama dari koleksi
            $firstValidation = $validations->first();
            
            if (is_null($firstValidation->notes)) {
                // Jika catatan dari entri pertama adalah null, lakukan update
                $firstValidation->update([
                    'is_validated' => true,
                    'notes' => $request->input('notes')
                ]);
            } else {
                // Jika catatan dari entri pertama tidak null, buat entri baru
                Validation::create([
                    'letter_id' => $letter->id,
                    'user_id' => $user->id,
                    'is_validated' => true,
                    'notes' => $request->input('notes')
                ]);
            }

            Validation::where('letter_id', $letter->id)
                ->where('user_id', $user->id)
                ->update(['is_validated' => true]);
        }

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

            if ($nextValidatorId) {
                $nextValidatorUser = User::find($nextValidatorId);
                if ($nextValidatorUser) {
                    $nextValidatorUser->notify(new LetterValidationNotification($letter));
                }
            }
        } else {
            // If there's no next validator, notify the secretary to request a letter code
            $secretary = User::whereHas('roles', function ($query) {
                $query->where('name', 'secretary');
            })->first();

            if ($secretary) {
                $secretary->notify(new RequestLetterCodeNotification($letter));
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