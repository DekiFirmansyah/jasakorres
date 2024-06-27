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
                    <h5 class="title">{{__(" Tambah Surat")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <form method="POST" action="{{ route('letters.store') }}" id="myForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="title">{{__(" Judul Surat")}}</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Masukkan judul surat">
                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="about">{{__(" Perihal Surat")}}</label>
                                    <input type="text" class="form-control" id="about" name="about"
                                        placeholder="Masukkan kategori perihal surat">
                                    @include('alerts.feedback', ['field' => 'about'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="purpose">{{__(" Dikirim Kepada")}}</label>
                                    <input type="text" class="form-control" id="purpose" name="purpose"
                                        placeholder="Masukkan data nama tujuan surat">
                                    @include('alerts.feedback', ['field' => 'purpose'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="validators">{{__(" Pilih Validator")}}</label>
                                    <p class="form-text text-muted">(Pilih pegawai yang harus menvalidasi
                                        surat)</p>
                                    <select name="validators[]" id="validators" class="form-control select2bs4" multiple
                                        required>
                                        @foreach($validators as $validator)
                                        <option value="{{ $validator->id }}">{{ $validator->name }} -
                                            {{ $validator->userDetail->posision }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'validators'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <label for="Letter File">{{__(" Dokumen Surat")}}</label>
                                <p class="form-text text-muted">(Ekstensi dokumen berupa .doc/.docx)</p>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="file" name="file">
                                    <label class="input-group-text" for="file">Unggah</label>
                                    @include('alerts.feedback', ['field' => 'file'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="description">Deskripsi Surat (Opsional/Boleh kosong)</label>
                                    <textarea rows="4" cols="80" class="form-control" id="description"
                                        name="description"
                                        placeholder="Disini untuk menambahkan deskripsi surat"></textarea>
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Simpan')}}</button>
                            <a class="btn btn-secondary btn-round text-white pull-right"
                                href="{{ route('letters.index') }}">Kembali</a>
                        </div>
                    </form>
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
</div>
@endsection