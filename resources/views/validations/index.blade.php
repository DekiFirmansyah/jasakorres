@extends('layouts.app', [
'namePage' => 'Validations Management',
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
                    <!-- <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('letters.create') }}">Add
                        Letter</a> -->
                    <h4 class="card-title">Surat yang Harus Divalidasi</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    @if($lettersToValidate->isEmpty())
                    <p>Tidak ada surat yang perlu divalidasi.</p>
                    @else
                    <div class="row">
                        @foreach($lettersToValidate as $letter)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $letter->title }}</h5>
                                    <p class="card-text">{{ Str::limit($letter->about, 100) }}</p>
                                    <p class="card-text"><small class="text-muted">Tujuan:
                                            {{ $letter->purpose }}</small>
                                    </p>

                                    <form action="{{ route('validations.validate', $letter->id) }}" method="POST"
                                        class="mt-2">
                                        @csrf
                                        <div class="form-group">
                                            <label for="notes">Catatan</label>
                                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                                placeholder="Here can be letter notes"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Validasi</button>
                                        <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                            class="btn btn-info pull-right">Lihat Dokumen</a>
                                    </form>
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