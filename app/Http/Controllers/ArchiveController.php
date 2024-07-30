<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    public function index()
    {
        $letters = Letter::whereHas('document', function($query) {
            $query->whereNotNull('letter_code');
        })->get();
        
        $lettersByDivision = $letters->groupBy(function($letter) {
            return $letter->user->userDetail->division->name;
        });

        $operation = $lettersByDivision->get('Operation', collect());
        $maintenance = $lettersByDivision->get('Maintenance', collect());
        $hcga = $lettersByDivision->get('Human Capital & General Affair', collect());
        $finance = $lettersByDivision->get('Finance', collect());
        
        return view('archives.index', compact('letters', 'operation', 'maintenance', 'hcga', 'finance'));
    }

    public function edit($id)
    {
        $letter = Letter::findOrFail($id);

        return view('archives.edit', compact('letter'));
    }

    public function update(Request $request, $id)
    {
        $letter = Letter::findOrFail($id);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $filePath = $letter->document->file ?? null;
        // Handle file upload
        if ($document = $request->file('file')) {
            // Delete the old file if it exists
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            $title = $letter->title;
            
            // Membersihkan title dari karakter yang tidak diinginkan
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-_ ]/', '', $title);
            $extension = $document->getClientOriginalExtension();

            $fileName = $sanitizedTitle . '.' . $extension;

            $filePath = Storage::disk('public')->putFileAs('document', $document, $fileName);

            // Update the file path in the document
            $letter->document->file = $filePath;
            $letter->document->save();
        }

        return redirect()->route('archives.index')->with('status', 'Dokumen surat berhasil diperbarui');
    }

    public function getTitle($id)
    {
        $letter = Letter::find($id);
        if ($letter) {
            return response()->json(['title' => $letter->title]);
        }
        return response()->json(['error' => 'Letter not found'], 404);
    }
}