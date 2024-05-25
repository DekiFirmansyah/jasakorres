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
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $letters = Letter::with('validators')->where('user_id', $user->id)->get();
        return view('letters.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar pengguna yang bisa menjadi validator
        $validators = User::role(['director', 'manager', 'secretary'])
            ->where('id', '!=', Auth::id())
            ->get();
            
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

        // Tambahkan validator
        foreach ($request->validators as $validatorId) {
            $letter->validations()->create([
                'user_id' => $validatorId,
                'is_validated' => false,
            ]);
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
        $letter = Letter::with('document')->findOrFail($id);
        
        // Ambil daftar pengguna yang bisa menjadi validator (gunakan Spatie roles)
        $validators = User::role(['director', 'manager', 'secretary'])->get();
        return view('letters.edit', compact('letter', 'validators'));
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

        $letter->validators()->sync($request->validators);

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
        
        // Get the associated file
        $filePath = $letter->file->file ?? null;
        
        // Delete the file from storage if it exists
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        // Delete the associated Document record
        $letter->file->delete();
        
        // Delete the letter
        $letter->delete();

        // Redirect with a success message
        return redirect()->route('letter.index')->with('status', 'Letter deleted successfully');
    }
}