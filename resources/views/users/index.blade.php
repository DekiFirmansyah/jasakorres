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
                    <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('user.create') }}">Tambah
                        user</a>
                    <h4 class="card-title">Data User</h4>
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
                                <th class="disabled-sorting text-right" style="width: 100px;">Aksi</th>
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
                                <th class="disabled-sorting text-right">Aksi</th>
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
                                    <button data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        class="btn btn-info btn-icon btn-sm btn-edit-password" title="Ubah password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-danger btn-icon btn-sm" title="Hapus"
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
                    <div id="updatePasswordForm" class="hidden">
                        <h5>Ubah Password untuk <span id="userName"></span></h5>
                        <form method="POST" action="" id="updatePasswordFormAction">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="password">{{__(" Password Baru")}}</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Masukkan password baru" required>
                                        @include('alerts.feedback', ['field' => 'password'])
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label for="password_confirmation">{{__(" Konfirmasi Password Baru")}}</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Masukkan konfirmasi password"
                                            required>
                                        @include('alerts.feedback', ['field' => 'password'])
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" id="cancelUpdatePassword">Batal</button>
                        </form>
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

@push('js')
<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        "pagingType": "simple_numbers",
        "pageLength": 10,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "columnDefs": [{
            "orderable": false,
            "targets": [6]
        }],
        "language": {
            "paginate": {
                "previous": "<i class='fa fa-angle-left'></i>",
                "next": "<i class='fa fa-angle-right'></i>"
            }
        }
    });
});

// Ubah password
document.addEventListener('DOMContentLoaded', function() {
    const editPasswordButtons = document.querySelectorAll('.btn-edit-password');
    const updatePasswordForm = document.getElementById('updatePasswordForm');
    const updatePasswordFormAction = document.getElementById('updatePasswordFormAction');
    const userNameSpan = document.getElementById('userName');
    const cancelUpdatePassword = document.getElementById('cancelUpdatePassword');

    editPasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const userName = this.getAttribute('data-name');

            // Update form action and user name
            updatePasswordFormAction.action = `/users/${userId}/update-password`;
            userNameSpan.textContent = userName;

            // Show the form
            updatePasswordForm.classList.remove('hidden');
        });
    });

    cancelUpdatePassword.addEventListener('click', function() {
        // Hide the form
        updatePasswordForm.classList.add('hidden');
    });
});
</script>
@endpush