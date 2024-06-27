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
                    <h5 class="title">{{__(" Edit User")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <form method="POST" action="{{ route('user.update', $user->id) }}" id="myForm"
                        enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="name">{{__(" Nama")}}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" placeholder="Masukkan nama">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="username">{{__(" Username")}}</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username', $user->username) }}" placeholder="Masukkan username">
                                    @include('alerts.feedback', ['field' => 'username'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="email">{{__(" Email")}}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="Masukkan email">
                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="roles">{{ __("Roles") }}</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="roles" name="roles"
                                        value="{{ $roles }}">
                                        @foreach($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ $user->roles->pluck('name')->contains($role) ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'roles'])
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="posision">{{__(" Jabatan")}}</label>
                                    <input type="text" class="form-control" id="posision" name="posision"
                                        value="{{ old('posision', $user->userDetail->posision) }}"
                                        placeholder="Masukkan jabatan">
                                    @include('alerts.feedback', ['field' => 'posision'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label for="nip">{{__(" NIP")}}</label>
                                    <input type="text" class="form-control" id="nip" name="nip"
                                        value="{{ old('nip', $user->userDetail->nip) }}"
                                        placeholder="Masukkan Nomor Induk Pegawai (NIP)">
                                    @include('alerts.feedback', ['field' => 'nip'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="division">{{__(" Divisi")}}</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="division_id"
                                        name="division_id">
                                        @foreach($divisions as $v)
                                        <option value="{{ $v->id }}"
                                            {{ $v->id == $user->userDetail->division_id ? 'selected' : '' }}>
                                            {{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'division_id'])
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
    <!-- <div class="alert alert-danger">
        <span>
            <b></b> This is a PRO feature!</span>
    </div> -->
    <!-- end row -->
</div>
@endsection