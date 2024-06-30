@extends('layouts.app', [
'namePage' => 'Manajemen User',
'class' => 'sidebar-mini',
'activePage' => 'users',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__(" Tambah User")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <form method="POST" action="{{ route('user.store') }}" id="myForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="name">{{__(" Nama")}}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukkan nama">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="username">{{__(" Username")}}</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Masukkan username">
                                    @include('alerts.feedback', ['field' => 'username'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="email">{{__(" Email")}}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Masukkan email">
                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="roles">{{__(" Roles")}}</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="roles"
                                        name="roles">
                                        @foreach($roles as $v)
                                        <option value="{{ $v }}" selected="selected">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="password">{{__(" Password")}}</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Masukkan password">
                                    @include('alerts.feedback', ['field' => 'password'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="password_confirmation">{{__(" Konfirmasi Password")}}</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Masukkan konfirmasi password">
                                    @include('alerts.feedback', ['field' => 'password_confirmation'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="phone">{{__(" Nomor Telepon")}}</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Masukkan nomor telepon">
                                    @include('alerts.feedback', ['field' => 'phone'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="nip">{{__(" NIP")}}</label>
                                    <input type="text" class="form-control" id="nip" name="nip"
                                        placeholder=" Masukkan Nomor Induk Pegawai (NIP)">
                                    @include('alerts.feedback', ['field' => 'nip'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="posision">{{__(" Jabatan")}}</label>
                                    <input type="text" class="form-control" id="posision" name="posision"
                                        placeholder="Masukkan jabatan">
                                    @include('alerts.feedback', ['field' => 'posision'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="division">{{__(" Divisi")}}</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="division_id"
                                        name="division_id">
                                        @foreach($divisions as $v)
                                        <option value="{{ $v->id }}" selected="selected">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Simpan')}}</button>
                            <a class="btn btn-secondary btn-round text-white pull-right"
                                href="{{ route('user.index') }}">Kembali</a>
                        </div>
                    </form>
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