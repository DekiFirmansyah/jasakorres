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
                    <h4>Detail Surat</h4>
                </div>
                <div class="card-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $letter->title }}</h5>
                            <p class="card-text"><strong>Perihal:</strong> {{ $letter->about }}</p>
                            <p class="card-text"><strong>Dikirim Kepada:</strong> {{ $letter->purpose }}</p>
                            <p class="card-text"><strong>Tanggal Buat:</strong>
                                {{ $letter->created_at->format('d/m/Y') }}</p>
                            <p class="card-text"><strong>Kode Surat:</strong>
                                @if($letter->document->letter_code)
                                {{ $letter->document->letter_code }}
                                @else
                                <span class="text-muted">Belum ada nomor surat</span>
                                @endif
                            </p>

                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                class="btn btn-info btn-round">Lihat Dokumen</a>

                            <button class="btn btn-warning btn-round mt-2" onclick="toggleCodeInputForm()">Input Kode
                                Surat</button>

                            <div id="codeInputForm" class="code-input-form mt-3" style="display: none;">
                                <form action="{{ route('letters.updateCode', $letter->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="letter_code">Kode Surat</label>
                                        <input type="text" name="letter_code" id="letter_code" class="form-control"
                                            placeholder="Masukkan kode surat" value="{{ old('letter_code') }}">
                                        @include('alerts.feedback', ['field' => 'letter_code'])
                                    </div>
                                    <button type="submit" class="btn btn-success btn-round">Simpan</button>
                                </form>
                            </div>

                            <script>
                            function toggleCodeInputForm() {
                                var form = document.getElementById('codeInputForm');
                                form.style.display = form.style.display === 'none' ? 'block' : 'none';
                            }
                            </script>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-secondary btn-round" href="{{ route('validations.index') }}">Kembali ke Daftar
                        Surat</a>
                </div>
            </div>
            <div class="mt-4">
                <h5>Daftar Kode Surat</h5>
                <embed src="{{ asset('storage/Daftar_Kode_Surat.pdf') }}" type="application/pdf" width="100%"
                    height="600px" />
            </div>
        </div>
    </div>
</div>
@endsection