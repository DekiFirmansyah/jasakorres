@extends('layouts.app', [
'namePage' => 'Manajemen Surat',
'class' => 'sidebar-mini',
'activePage' => 'letters',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__(" Detail Surat")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $letter->title }}</h5>
                            <p class="card-text"><strong>Perihal:</strong> {{ $letter->about }}</p>
                            <p class="card-text"><strong>Dikirim Kepada:</strong> {{ $letter->purpose }}</p>
                            <p class="card-text"><strong>Tanggal Buat:</strong>
                                {{ $letter->created_at->format('d/m/Y') }}</p>
                            <p class="card-text"><strong>Kode Surat:</strong>
                                {{ $letter->document->letter_code ?? 'Belum ada nomor surat' }}</p>

                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                class="btn btn-info btn-round">Lihat Dokumen</a>
                        </div>
                    </div>

                    <h3>Riwayat Catatan</h3>

                    <table class="table table-borderless">
                        <thead class="small-font">
                            <tr>
                                <th>Nama Validator</th>
                                <th>Jabatan</th>
                                <th>Catatan</th>
                                <th>Tanggal</th>
                                <th>File Revisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notesHistory as $note)
                            <tr>
                                <td>{{ $note->user->name }}</td>
                                <td>{{ $note->user->userDetail->posision }}</td>
                                <td>{{ $note->notes ?? 'Tidak ada catatan' }}</td>
                                <td>{{ \Carbon\Carbon::parse($note->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    @if($note->revised_file)
                                    <a href="{{ asset('storage/' . $note->revised_file) }}" target="_blank"
                                        class="btn btn-info btn-round">Lihat File Revisi</a>
                                    @else
                                    Tidak Ada File
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada riwayat catatan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer ">
                    <a href="{{ route('letters.edit', $letter->id) }}" class="btn btn-primary btn-round">Edit Surat</a>
                    <a class="btn btn-secondary btn-round text-white pull-right"
                        href="{{ route('letters.index') }}">Kembali ke Daftar Surat</a>
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
</div>
@endsection