<?php

namespace App\Exports;

use App\Models\Notebook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class NotebookExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        // Ambil data dengan relasi yang sesuai
        $notebooks = Notebook::with(['letter' => function($query) {
            $query->whereMonth('created_at', Carbon::parse($this->month)->month)
                  ->whereYear('created_at', Carbon::parse($this->month)->year);
        }])->get();

        // Cek apakah data tersedia
        if ($notebooks->isEmpty()) {
            return collect([]);
        }

        // Map data untuk export
        $data = $notebooks->flatMap(function($notebook) {
            return $notebook->letter->get()->map(function($letter) use ($notebook) {
                return [
                    'ID' => $notebook->id,
                    'Tanggal Surat' => $letter->updated_at->format('d M Y'),
                    'No Surat' => $letter->document->letter_code,
                    'Perihal Surat' => $letter->title,
                    'Dikirim Kepada' => $notebook->destination_name,
                    'Alamat Penerima' => $notebook->destination_address,
                    'Tanggal Kirim' => $notebook->date_sent->format('d M Y'),
                    'Keterangan' => $notebook->description,
                ];
            });
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Surat',
            'No Surat',
            'Perihal Surat',
            'Dikirim Kepada',
            'Alamat Penerima',
            'Tanggal Kirim',
            'Keterangan',
        ];
    }
}