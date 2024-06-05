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
        // Initialize file path
        $filePath = null;

        // Handle file upload
        if ($document = $request->file('file')) {
            $fileName = Str::random(20) . '.' . $document->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('document', $document, $fileName);
        }

        // Create a Document record
        $file = Document::create([
            'file' => $filePath,
        ]);

        // Create a Letter record
        $letter = Letter::create([
            'title' => $request->input('title'),
            'about' => $request->input('about'),
            'purpose' => $request->input('purpose'),
            'user_id' => auth()->id(),
            'description' => $request->input('description'),
            'document_id' => $file->id,
        ]);

        $creator = Auth::user();

        // Tentukan peran tujuan pertama berdasarkan peran pembuat surat
        $firstRoleToNotify = $creator->hasRole('secretary') ? 'manager' : 'secretary';

        // Tambahkan validator dan kirim notifikasi
        foreach ($request->validators as $validatorId) {
            $letter->validations()->create([
                'user_id' => $validatorId,
                'is_validated' => false,
            ]);

            // Kirim notifikasi ke validator pertama yang memiliki peran yang sesuai
            $validator = User::find($validatorId);
            if (is_string($firstRoleToNotify) && $validator->hasRole($firstRoleToNotify)) {
                $validator->notify(new LetterValidationNotification($letter));
                $firstRoleToNotify = null; // Pastikan hanya satu notifikasi pertama yang dikirim
            }
        }

        // Redirect with a success message
        return redirect()->route('letters.index')->with('status', 'Letter created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Letter $letter)
    {
        // Find the letter by its ID
        $letter = Letter::findOrFail($id);
        
        // Return a view to display the form for editing the letter
        return view('letters.edit', compact('letter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the letter by its ID
        $letter = Letter::with('document', 'validators')->findOrFail($id);

        return view('letters.edit', compact('letter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterRequest $request, $id)
    {
        // Find the letter by its ID
        $letter = Letter::findOrFail($id);
        
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

        // Update the letter data
        $letter->update($request->only(['title', 'about', 'purpose', 'description']));
        
        // Update the associated Document record
        $letter->document->update([
            'file' => $filePath,
        ]);

        // Array of roles in hierarchical order
        $rolesHierarchy = ['secretary', 'manager', 'general-manager', 'general-director', 'executive-director'];

        // Find all validators who haven't validated the letter yet
        $validators = $letter->validations()->where('is_validated', false)->with('user')->get();

        // Check each role in the hierarchy to find the next validator
        foreach ($rolesHierarchy as $role) {
            $nextValidator = $validators->filter(function($validator) use ($role) {
                return $validator->user->hasRole($role);
            })->first();

            // If the next validator exists, send notification
            if ($nextValidator) {
                $nextValidator->user->notify(new LetterValidationNotification($letter));
                break; // Stop after sending notification to the first found next validator
            }
        }
        // Redirect with a success message
        return redirect()->route('letters.index')->with('status', 'Letter updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the letter by its ID
        $letter = Letter::findOrFail($id);
        
        // Get the associated file, if available
        $file = optional($letter->document)->file;
        
        // Delete the file from storage if it exists
        if ($file && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
            
            // Delete the associated Document record
            $letter->document->delete();
        }
        
        // Delete the letter
        $letter->delete();

        // Redirect with a success message
        return back()->withStatus(__('Letter deleted successfully'));
    }

}