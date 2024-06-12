<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use App\Models\Notebook;
use Carbon\Carbon;
use App\Http\Requests\NotebookRequest;
use PDF;
use App\Exports\NotebookExport;
use Maatwebsite\Excel\Facades\Excel;

class NotebookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notebooks = Notebook::with('letter')->get();
        return view('notebooks.index', compact('notebooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $letters = Letter::with('user.userDetail.division')->get()->groupBy(function ($letter) {
            return $letter->user->userDetail->division->name;
        });
    
        return view('notebooks.create', compact('letters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NotebookRequest $request)
    {
    
        $dateSent = Carbon::parse($request->input('date_sent'),);

        Notebook::create([
            'letter_id' => $request->input('letter_id'),
            'date_sent' => $dateSent,
            'destination_name' => $request->input('destination_name'),
            'destination_address' => $request->input('destination_address'),
            'description' => $request->input('description'),
        ]);
    
        return redirect()->route('notebooks.index')->with('status', 'Agenda created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notebooks = Notebook::with('letter')->findOrFail($id);
        return view('notebooks.show', compact('notebooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $notebook = Notebook::findOrFail($id);
        $letters = Letter::with('user.userDetail.division')->get()->groupBy(function ($letter) {
            return $letter->user->userDetail->division->name;
        });

        return view('notebooks.edit', compact('notebook', 'letters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NotebookRequest $request, string $id)
    {
        $notebook = Notebook::findOrFail($id);
        $notebook->update($request->all());

        return redirect()->route('notebooks.index')->with('status', 'Agenda updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notebook = Notebook::findOrFail($id);
        $notebook->delete();

        return redirect()->route('notebooks.index')->with('status', 'Agenda Surat Berhasil Dihapus');
    }

    public function filter(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->month;
        $notebooks = Notebook::with('letter')
            ->whereYear('date_sent', '=', date('Y', strtotime($month)))
            ->whereMonth('date_sent', '=', date('m', strtotime($month)))
            ->get();

        return view('notebooks.index', compact('notebooks'));
    }
    
    public function exportPDF(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->month;
        $notebooks = Notebook::with('letter')
            ->whereYear('date_sent', '=', date('Y', strtotime($month)))
            ->whereMonth('date_sent', '=', date('m', strtotime($month)))
            ->get();

        $pdf = PDF::loadView('notebooks.laporan_pdf', compact('notebooks', 'month'));
        return $pdf->download('agenda_surat_' . $month . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month');
        
        // Debugging: Check if month is set correctly
        if (!$month) {
            return back()->with('errors', 'Month is required');
        }

        // Debugging: Check data from Notebook model
        $notebooks = Notebook::with('letter')
            ->whereHas('letter', function ($query) use ($month) {
                $query->whereMonth('created_at', Carbon::parse($month)->month)
                    ->whereYear('created_at', Carbon::parse($month)->year);
            })->get();

        // Check if notebooks data is available
        if ($notebooks->isEmpty()) {
            return back()->with('errors', 'No data available for the selected month');
        }

        return Excel::download(new NotebookExport($month), 'agenda_surat_'. Carbon::parse($month)->format('Y_m') .'.xlsx');
    }
}