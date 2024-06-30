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
                    <h4 class="card-title">Validasi Surat</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <ul class="nav nav-tabs d-flex flex-wrap" id="validationTabs" role="tablist">
                            <li class="nav-item flex-fill">
                                <a class="nav-link active text-center" id="your-validations-tab" data-toggle="tab"
                                    href="#your-validations" role="tab" aria-controls="your-validations"
                                    aria-selected="true">
                                    <span class="btn-icon"><i class="fas fa-tasks"></i></span>
                                    <span class="text">Surat yang Perlu Divalidasi</span>
                                </a>
                            </li>
                            @hasanyrole('secretary')
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="apply-letter-code-tab" data-toggle="tab"
                                    href="#apply-letter-code" role="tab" aria-controls="apply-letter-code"
                                    aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-key"></i></span>
                                    <span class="text">Surat yang Perlu Nomor Surat</span>
                                </a>
                            </li>
                            @endhasanyrole
                        </ul>
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="tab-content" id="validationTabsContent">
                        <!-- Tab for Your validations -->
                        <div class="tab-pane fade show active" id="your-validations" role="tabpanel"
                            aria-labelledby="your-validations-tab">
                            <h5 class="mt-3">Surat yang Harus Divalidasi</h5>
                            @if($lettersToValidate->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang perlu divalidasi.
                            </div>
                            @else
                            <div class="row">
                                @foreach($lettersToValidate as $letter)
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $letter->title }}</h5>
                                            <p class="card-text"><small class="text-muted">Dikirim Kepada:
                                                    {{ $letter->purpose }}</small>
                                            </p>

                                            <form action="{{ route('validations.validate', $letter->id) }}"
                                                method="POST" class="mt-2">
                                                @csrf
                                                <p class="form-text text-center"><b>"Jika surat sudah sesuai,
                                                        maka form catatan dan unggah file dikosongi!"</b>
                                                </p>
                                                <div class="form-group">
                                                    <label for="notes">Catatan (Optional)</label>
                                                    <textarea name="notes" id="notes" class="form-control" rows="3"
                                                        placeholder="Tambahkan catatan revisi surat jika diperlukan"></textarea>
                                                    @include('alerts.feedback', ['field' => 'notes'])
                                                </div>
                                                <label for="File SUrat">{{__(" File Surat (Optional)")}}</label>
                                                <p class="form-text text-muted">Unggah file yang telah diberi catatan
                                                    disini<br>
                                                    (Ekstensi dokumen berupa .doc/.docx)
                                                </p>
                                                <p class="form-text text-muted"></p>
                                                <div class="input-group">
                                                    <input type="file" name="file" class="form-control" id="file">
                                                    <label class="input-group-text" for="file">Unggah</label>
                                                    @include('alerts.feedback', ['field' => 'file'])
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-primary btn-round">Validasi</button>
                                                <a href="{{ asset('storage/' . $letter->document->file) }}"
                                                    target="_blank" rel="tooltip"
                                                    class="btn btn-info btn-round pull-right">Lihat
                                                    Dokumen</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="apply-letter-code" role="tabpanel"
                            aria-labelledby="apply-letter-code-tab">
                            <h5 class="mt-3">Permintaan Nomor Surat</h5>
                            @if($requestLetterCode->isEmpty())
                            <div class="alert alert-info">
                                Semua surat sudah terdapat kode surat.
                            </div>
                            @else
                            <div class="row">
                                @foreach($requestLetterCode as $letter)
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $letter->title }}</h5>
                                            <p class="card-text"><small class="text-muted">Dikirim Kepada:
                                                    {{ $letter->purpose }}</small>
                                            </p>

                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                class="btn btn-info btn-round pull-right">Lihat Dokumen</a>

                                            <button class="btn btn-round btn-warning mt-2"
                                                onclick="showCodeInputForm({{ $letter->id }})">Input Kode Surat</button>

                                            <div id="codeInputForm-{{ $letter->id }}" class="code-input-form"
                                                style="display: none;">
                                                <form action="{{ route('letters.updateCode', $letter->id) }}"
                                                    method="POST" class="mt-2">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="letter_code">Kode Surat</label>
                                                        <input type="text" name="letter_code" id="letter_code"
                                                            class="form-control" placeholder="Masukkan kode surat">
                                                        @include('alerts.feedback', ['field' => 'letter_code'])
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-success btn-round">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <h5>Daftar Kode Surat</h5>
                                <embed src="{{ asset('storage/Daftar_Kode_Surat.pdf') }}" type="application/pdf"
                                    width="100%" height="600px" />
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
    <!-- end row -->
</div>
@endsection