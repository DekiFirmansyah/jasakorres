@extends('layouts.app', [
'namePage' => 'Manajemen Validasi Surat',
'class' => 'sidebar-mini',
'activePage' => 'validations',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__(" Detail Validasi Surat")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Surat</h5>
                            <p><strong>Judul:</strong> {{ $letter->title }}</p>
                            <p><strong>Perihal:</strong> {{ $letter->about }}</p>
                            <p><strong>Dikirim Kepada:</strong> {{ $letter->purpose }}</p>
                            <p><strong>Tanggal Dibuat:</strong> {{ $letter->created_at->format('d/m/Y') }}</p>
                            <p><strong>Kode Surat:</strong>
                                {{ $letter->document->letter_code ?? 'Belum ada nomor surat' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Dokumen</h5>
                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                class="btn btn-info btn-round">Lihat Dokumen</a>
                        </div>
                    </div>

                    <!-- Riwayat Catatan -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Riwayat Catatan</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Validator</th>
                                        <th>Catatan</th>
                                        <th>File Revisi</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($historyNotes as $note)
                                    <tr>
                                        <td>{{ $note->user->name }}</td>
                                        <td>{{ $note->notes ?? 'Tidak ada catatan' }}</td>
                                        <td>
                                            @if($note->revised_file)
                                            <a href="{{ asset('storage/' . $note->revised_file) }}" target="_blank"
                                                class="btn btn-info btn-round">Lihat File Revisi</a>
                                            @else
                                            Tidak ada file
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($note->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada riwayat catatan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <a class="btn btn-secondary btn-round" href="{{ route('validations.index') }}">Kembali ke Daftar
                        Validasi Surat</a>
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
</div>
@endsection