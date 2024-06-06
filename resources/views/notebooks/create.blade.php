@extends('layouts.app', [
'namePage' => 'Agenda Management',
'class' => 'sidebar-mini',
'activePage' => 'notebooks',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__(" Add Agenda")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <form method="POST" action="{{ route('notebooks.store') }}" id="myForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="letter_id">{{__(" Pilih Surat")}}</label>
                                    <select class="form-control" id="letter_id" name="letter_id" required>
                                        @foreach($letters as $division => $divisionLetters)
                                        <optgroup label="{{ $division }}">
                                            @foreach($divisionLetters as $letter)
                                            <option value="{{ $letter->id }}">{{ $letter->title }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="date_sent">{{__(" Pilih Tanggal Kirim")}}</label>
                                    <input type="date" class="form-control" id="date_sent" name="date_sent">
                                    @include('alerts.feedback', ['field' => 'date_sent'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="destination_name">{{__(" Dikirim Kepada")}}</label>
                                    <input type="text" class="form-control" id="destination_name"
                                        name="destination_name" placeholder="Masukkan data nama tujuan">
                                    @include('alerts.feedback', ['field' => 'destination_name'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="destination_address">{{__(" Alamat Tujuan")}}</label>
                                    <input type="text" class="form-control" id="destination_address"
                                        name="destination_address" placeholder="Masukkan data alamat tujuan">
                                    @include('alerts.feedback', ['field' => 'destination_address'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>Catatan (opsional)</label>
                                    <textarea rows="4" cols="80" class="form-control" id="description"
                                        name="description"
                                        placeholder="Disini tambahkan catatan untuk agenda surat"></textarea>
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Save')}}</button>
                            <a class="btn btn-secondary btn-round text-white pull-right"
                                href="{{ route('notebooks.index') }}">Back</a>
                        </div>
                    </form>
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