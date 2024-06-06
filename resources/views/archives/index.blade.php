@extends('layouts.app', [
'namePage' => 'Archives Management',
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
                    <h4 class="card-title">Archives</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs" id="letterTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-letters-tab" data-toggle="tab" href="#all-letters"
                                    role="tab" aria-controls="all-letters" aria-selected="true">Semua Surat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="operation-tab" data-toggle="tab" href="#operation" role="tab"
                                    aria-controls="operation" aria-selected="false">Operation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hcga-tab" data-toggle="tab" href="#hcga" role="tab"
                                    aria-controls="hcga" aria-selected="false">HC&GA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="finance-tab" data-toggle="tab" href="#finance" role="tab"
                                    aria-controls="finance" aria-selected="false">Finance</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="maintenance-tab" data-toggle="tab" href="#maintenance"
                                    role="tab" aria-controls="maintenance" aria-selected="false">Maintenance</a>
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
                                                title="Update Dokumen">
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
                                            <a type="button" href="{{ route('archives.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
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
                                            <a type="button" href="{{ route('archives.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
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
                                            <a type="button" href="{{ route('archives.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
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
                                            <a type="button" href="{{ route('archives.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm btn-update-file"
                                                title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
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
                                <label class="input-group-text" for="file">Upload</label>
                                @include('alerts.feedback', ['field' => 'file'])
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-secondary" id="cancelUpdateFile">Cancel</button>
                        </form>
                    </div>
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

@push('js')
<script>
function showNote(validatorName, notes) {
    if (notes) {
        Swal.fire({
            title: 'Catatan dari ' + validatorName,
            text: notes,
            icon: 'info'
        });
    } else {
        Swal.fire({
            title: 'Catatan dari ' + validatorName,
            text: 'Tidak ada catatan',
            icon: 'warning'
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const updateFileButtons = document.querySelectorAll('.btn-update-file');
    const updateFileForm = document.getElementById('updateFileForm');
    const updateFileFormAction = document.getElementById('updateFileFormAction');
    const cancelUpdateFile = document.getElementById('cancelUpdateFile');

    updateFileButtons.forEach(button => {
        button.addEventListener('click', function() {
            const letterId = this.getAttribute('data-id');
            updateFileFormAction.action = `/archives/${letterId}/update`;
            updateFileForm.classList.remove('hidden');
            alert("You are about to update the file for this letter.");
        });
    });

    cancelUpdateFile.addEventListener('click', function() {
        updateFileForm.classList.add('hidden');
    });
});
</script>
@endpush