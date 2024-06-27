@extends('layouts.app', [
'namePage' => 'Manajemen Arsip Surat',
'class' => 'sidebar-mini',
'activePage' => 'archives',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Arsip Surat</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs d-flex flex-wrap" id="letterTabs" role="tablist">
                            <li class="nav-item flex-fill">
                                <a class="nav-link active text-center" id="all-letters-tab" data-toggle="tab"
                                    href="#all-letters" role="tab" aria-controls="all-letters" aria-selected="true">
                                    <span class="btn-icon"><i class="fas fa-envelope"></i></span>
                                    <span class="text">Semua Surat</span>
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="operation-tab" data-toggle="tab" href="#operation"
                                    role="tab" aria-controls="operation" aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-cogs"></i></span>
                                    <span class="text">Operation</span>
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="hcga-tab" data-toggle="tab" href="#hcga" role="tab"
                                    aria-controls="hcga" aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-users"></i></span>
                                    <span class="text">Human Capital & General Affair</span>
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="finance-tab" data-toggle="tab" href="#finance"
                                    role="tab" aria-controls="finance" aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-dollar-sign"></i></span>
                                    <span class="text">Finance</span>
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="maintenance-tab" data-toggle="tab"
                                    href="#maintenance" role="tab" aria-controls="maintenance" aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-tools"></i></span>
                                    <span class="text">Maintenance</span>
                                </a>
                            </li>
                        </ul>
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="tab-content" id="letterTabsContent">
                        <!-- Tab for Your Archives -->
                        <div class="tab-pane fade show active" id="all-letters" role="tabpanel"
                            aria-labelledby="all-letters-tab">
                            <h5 class="mt-3">Arsip Semua Surat</h5>
                            @if($letters->isEmpty())
                            <div class="alert alert-info">
                                Belum ada surat yang di arsipkan.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Divisi</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Divisi</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right">Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($letters as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->document->letter_code }}</td>
                                        <td>{{ $letter->user->userDetail->division->name }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <button data-id="{{ $letter->id }}"
                                                class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Perbarui Dokumen">
                                                <i class="far fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <!-- Tab for Operation Archives -->
                        <div class="tab-pane fade" id="operation" role="tabpanel" aria-labelledby="operation-tab">
                            <h5 class="mt-3">Arsip Surat Divisi Operation</h5>
                            @if($operation->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang diarsipkan pada divisi Operation.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($operation as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->document->letter_code }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <button data-id="{{ $letter->id }}"
                                                class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Perbarui Dokumen">
                                                <i class="far fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <!-- Tab for HC&GA Archives -->
                        <div class="tab-pane fade" id="hcga" role="tabpanel" aria-labelledby="hcga-tab">
                            <h5 class="mt-3">Arsip Surat Divisi Human Capital & General Affair</h5>
                            @if($hcga->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang diarsipkan pada divisi Human Capital & General Affair.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($hcga as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->document->letter_code }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <button data-id="{{ $letter->id }}"
                                                class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Perbarui Dokumen">
                                                <i class="far fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <!-- Tab for Finance Archives -->
                        <div class="tab-pane fade" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                            <h5 class="mt-3">Arsip Surat Divisi Finance</h5>
                            @if($finance->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang diarsipkan pada divisi Finance.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($finance as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->document->letter_code }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <button data-id="{{ $letter->id }}"
                                                class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Perbarui Dokumen">
                                                <i class="far fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <!-- Tab for Maintenance Archives -->
                        <div class="tab-pane fade" id="maintenance" role="tabpanel" aria-labelledby="maintenance-tab">
                            <h5 class="mt-3">Arsip Surat Divisi Maintenance</h5>
                            @if($maintenance->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang diarsipkan pada divisi Maintenance.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Perihal</th>
                                        <th>No. Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Tanggal Surat</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($maintenance as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->document->letter_code }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <button data-id="{{ $letter->id }}"
                                                class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Perbarui Dokumen">
                                                <i class="far fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                    <!-- Hidden Update Form -->
                    <div id="updateFileForm" class="hidden">
                        <form method="POST" action="" id="updateFileFormAction" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" id="file">
                                <label class="input-group-text" for="file">Unggah</label>
                                @include('alerts.feedback', ['field' => 'file'])
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" id="cancelUpdateFile">Batal</button>
                        </form>
                    </div>
                </div>
                <!-- end content-->
            </div>
            <!--  end card  -->
        </div>
        <!-- end col-md-12 -->
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateFileButtons = document.querySelectorAll('.btn-update-file');
    const updateFileForm = document.getElementById('updateFileForm');
    const updateFileFormAction = document.getElementById('updateFileFormAction');
    const cancelUpdateFile = document.getElementById('cancelUpdateFile');

    updateFileButtons.forEach(button => {
        button.addEventListener('click', function() {
            const letterId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan memperbarui data dokumen surat',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui sekarang!',
                cancelButtonText: 'Tidak, batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateFileFormAction.action = `/archives/${letterId}/update`;
                    updateFileForm.classList.remove('hidden');
                }
            });
        });
    });

    cancelUpdateFile.addEventListener('click', function() {
        updateFileForm.classList.add('hidden');
    });
});

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
</script>
@endpush