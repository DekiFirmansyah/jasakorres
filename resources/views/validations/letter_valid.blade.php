@extends('layouts.app', [
'namePage' => 'Letter Valid Page',
'class' => 'sidebar-mini',
'activePage' => 'letterValids',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <!-- <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('letters.create') }}">Add
                        Letter</a> -->
                    <h4 class="card-title">Data Surat yang Valid</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    @if($fullyValidatedLetters->isEmpty())
                    <div class="alert alert-info">
                        Tidak ada surat yang sudah tervalidasi.
                    </div>
                    @else
                    <div class="row">
                        @foreach($fullyValidatedLetters as $letter)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $letter->title }}</h5>
                                    <p class="card-text">{{ Str::limit($letter->about, 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Dikirim Kepada:
                                            {{ $letter->purpose }}</small>
                                    </p>

                                    @if($letter->document && $letter->document->file)
                                    <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                        class="btn btn-primary">Lihat Dokumen</a>
                                    @else
                                    <span class="text-danger">Dokumen tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
    <!-- <div class="alert alert-danger">
        <span>
            <b></b> This is a PRO feature!</span>
    </div> -->
    <!-- end row -->
</div>
@endsection