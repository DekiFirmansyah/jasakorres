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
                    <ul>
                        @foreach($lettersToValidate as $letter)
                        <li>
                            <strong>{{ $letter->title }}</strong><br>
                            {{ $letter->about }}<br>
                            <em>Divisi: {{ $letter->purpose }}</em><br>
                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank">Lihat
                                Dokumen</a><br>
                            <form action="{{ route('validations.validate', $letter->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Validasi</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
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