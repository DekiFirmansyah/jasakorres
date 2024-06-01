@extends('layouts.app', [
'namePage' => 'Users Management',
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
                    <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('user.create') }}">Add
                        user</a>
                    <h4 class="card-title">Users</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="width: 20px;">No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr style="text-align: center;">
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Role User</th>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($datas as $key=>$user)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->userDetail->nip }}</td>
                                <td>{{ $user->userDetail->division->name }}</td>
                                <td>{{ $user->userDetail->posision }}</td>
                                <td class="text-center">
                                    @foreach($user->roles as $key => $item)
                                    <span class="badge bg-info">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-right">
                                    <a type="button" href="{{ route('user.edit', $user->id) }}" rel="tooltip"
                                        class="btn btn-success btn-icon btn-sm" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-icon btn-sm" title="Delete"
                                        onclick="confirmDelete({{ $user->id }})">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach()
                        </tbody>
                    </table>
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