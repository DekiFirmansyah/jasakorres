@extends('layouts.app', [
'class' => 'sidebar-mini ',
'namePage' => 'Profil User',
'activePage' => 'profile',
'activeNav' => '',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__(" Edit Profil")}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        @include('alerts.success')
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label>{{__(" Nama")}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name"
                                        value="{{ old('name', auth()->user()->name) }}">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label>{{__(" Username")}}</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username"
                                        value="{{ old('username', auth()->user()->username) }}">
                                    @include('alerts.feedback', ['field' => 'username'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{__(" Email address")}}</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email"
                                        value="{{ old('email', auth()->user()->email) }}">
                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <label for="Foto Profil">{{__(" Foto Profil")}}</label>
                                <div class="input-group">
                                    <input type="file" name="photo" class="form-control" id="photo">
                                    <label class="input-group-text" for="photo">Unggah</label>
                                    @include('alerts.feedback', ['field' => 'photo'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 pr-1">
                                <div class="form-group">
                                    <label>Quotes</label>
                                    <textarea rows="4" cols="80" class="form-control" id="description"
                                        name="description"
                                        placeholder="Disini untuk mendeskripsikan tentang diri anda">{{ old('description', auth()->user()->userDetail->description) }}</textarea>
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Simpan')}}</button>
                        </div>
                        <hr class="half-rule" />
                    </form>
                </div>
                <div class="card-header">
                    <h5 class="title">{{__("Password")}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')
                        @include('alerts.success', ['key' => 'password_status'])
                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label>{{__(" Password Lama")}}</label>
                                    <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="old_password" placeholder="{{ __('Masukkan password lama') }}"
                                        type="password" required>
                                    @include('alerts.feedback', ['field' => 'old_password'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label>{{__(" Password Baru")}}</label>
                                    <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Masukkan password baru') }}" type="password" name="password"
                                        required>
                                    @include('alerts.feedback', ['field' => 'password'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label>{{__(" Konfirmasi Password Baru")}}</label>
                                    <input class="form-control" placeholder="{{ __('Masukkan konfirmasi password') }}"
                                        type="password" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round ">{{__('Ganti Password')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <img src="{{asset('assets')}}/img/bg5.jpg" alt="...">
                </div>
                <div class="card-body">
                    <div class="author">
                        <img class="avatar border-gray"
                            src="{{ auth()->user()->userDetail->photo ? asset('storage/' . auth()->user()->userDetail->photo) : asset('assets/img/default-avatar.png') }}"
                            alt="...">
                        <h5 class="title">{{ auth()->user()->name }}</h5>
                        <h7> {{ auth()->user()->userDetail->nip }} - {{ auth()->user()->userDetail->posision }} </h7>
                        <p class="description">
                            {{ auth()->user()->userDetail->description }}
                        </p>
                    </div>
                </div>
                <hr>
                <div class="button-container">
                    <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                        <i class="fab fa-facebook-square"></i>
                    </button>
                    <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                        <i class="fab fa-google-plus-square"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection