<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\Document;
use App\Http\Requests\UpdateLetterRequest;
use App\Http\Requests\StoreLetterRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Validation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LetterValidationNotification;

class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $letters = Letter::with('validators')->where('user_id', $user->id)->get();
        
        $fullyValidatedLetters = Letter::where('user_id', $user->id)->whereHas('validators', function($query) {
            $query->where('is_validated', true);
        }, '=', function ($query) {
            $query->selectRaw('COUNT(*)')
                ->from('validations')
                ->whereColumn('letter_id', 'letters.id');
        })->whereHas('document', function($query) {
            $query->whereNotNull('letter_code');
        })->get();

        return view('letters.index', compact('letters', 'fullyValidatedLetters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
        $validators = User::role(['executive-director', 'general-director', 'general-manager', 'manager', 'secretary'])->get();
            
        return view('letters.create', compact('validators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLetterRequest $request)
    {
        $filePath = null;

        if ($document = $request->file('file')) {
            $fileName = Str::random(20) . '.' . $document->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('document', $document, $fileName);
        }

        $file = Document::create([
            'file' => $filePath,
        ]);

        $letter = Letter::create([
            'title' => $request->input('title'),
            'about' => $request->input('about'),
            'purpose' => $request->input('purpose'),
            'user_id' => auth()->id(),
            'description' => $request->input('description'),
            'document_id' => $file->id,
        ]);

        $creator = Auth::user();

        $firstRoleToNotify = $creator->hasRole('secretary') ? 'manager' : 'secretary';

        foreach ($request->validators as $validatorId) {
            $letter->validations()->create([
                'user_id' => $validatorId,
                'is_validated' => false,
            ]);

            if ($firstRoleToNotify !== null) {
                $validator = User::find($validatorId);
                if ($validator && $validator->hasRole($firstRoleToNotify)) {
                    $validator->notify(new LetterValidationNotification($letter));
                    $firstRoleToNotify = null;
                }
            }
        }

        return redirect()->route('letters.index')->with('status', 'Surat berhasil dibuat dan notifikasi telah dikirim ke validator');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Letter $letter)
    {
        $letter = Letter::findOrFail($id);
        
        return view('letters.edit', compact('letter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $letter = Letter::with('document', 'validators')->findOrFail($id);

        return view('letters.edit', compact('letter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterRequest $request, $id)
    {
        $letter = Letter::findOrFail($id);
        
        $filePath = $letter->document->file ?? null;

        if ($file = $request->file('file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('document', $file, $fileName);
            $letter->document->update([
                'file' => $filePath,
            ]);

            $letter->touch();
        } else {
            $letter->update($request->only(['title', 'about', 'purpose', 'description']));
            
            $letter->document->update([
                'file' => $filePath,
            ]);
        }

        $rolesHierarchy = ['secretary', 'manager', 'general-manager', 'general-director', 'executive-director'];

        $validators = $letter->validations()->where('is_validated', false)->with('user')->get();

        foreach ($rolesHierarchy as $role) {
            $nextValidator = $validators->filter(function($validator) use ($role) {
                return $validator->user->hasRole($role);
            })->first();

            if ($nextValidator) {
                $nextValidator->user->notify(new LetterValidationNotification($letter));
                break;
            }
        }
        
        return redirect()->route('letters.index')->with('status', 'Surat berhasil diperbarui dan notifikasi telah dikirim ke validator');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $letter = Letter::findOrFail($id);
        
        $file = optional($letter->document)->file;
        
        if ($file && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
            
            $letter->document->delete();
        }
        
        $letter->delete();

        return back()->withStatus(__('Surat berhasil dihapus'));
    }

}