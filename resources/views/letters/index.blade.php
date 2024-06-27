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
                    <a class="btn btn-primary btn-round text-white pull-right"
                        href="{{ route('letters.create') }}">Tambah Surat</a>
                    <h4 class="card-title">Data Surat</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        @include('alerts.success')
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs d-flex flex-wrap" id="letterTabs" role="tablist">
                            <li class="nav-item flex-fill">
                                <a class="nav-link active text-center" id="your-letters-tab" data-toggle="tab"
                                    href="#your-letters" role="tab" aria-controls="your-letters" aria-selected="true">
                                    <span class="btn-icon"><i class="fas fa-envelope"></i></span>
                                    <span class="text">Surat yang Anda Buat</span>
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link text-center" id="validated-letters-tab" data-toggle="tab"
                                    href="#validated-letters" role="tab" aria-controls="validated-letters"
                                    aria-selected="false">
                                    <span class="btn-icon"><i class="fas fa-check"></i></span>
                                    <span class="text">Surat yang Sudah Divalidasi</span>
                                </a>
                            </li>
                        </ul>
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <div class="tab-content" id="letterTabsContent">
                        <!-- Tab for Your Letters -->
                        <div class="tab-pane fade show active" id="your-letters" role="tabpanel"
                            aria-labelledby="your-letters-tab">
                            <h5 class="mt-3">Surat yang Anda Buat</h5>
                            @if($letters->isEmpty())
                            <div class="alert alert-info">
                                Anda belum membuat surat apapun.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Judul</th>
                                        <th>Perihal</th>
                                        <th>Dikirim Kepada</th>
                                        <th>Validator</th>
                                        <th>Tgl Buat</th>
                                        <th class="disabled-sorting text-right" style="width: 70px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Perihal</th>
                                        <th>Dikirim Kepada</th>
                                        <th>Validator</th>
                                        <th>Tgl Buat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($letters as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->about }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>
                                            <ul>
                                                @foreach($letter->validators as $validator)
                                                <li>{{ $validator->name }}:
                                                    {{ $validator->pivot->is_validated ? 'Sudah' : 'Belum' }} -
                                                    <button class="btn btn-info btn-icon btn-sm" title="Catatan"
                                                        onclick="showNote('{{ $validator->name }}', '{{ $validator->pivot->notes }}')">
                                                        <i class="far fa-comments"></i>
                                                    </button>
                                                </li>
                                                <br>
                                                @endforeach
                                                <li>
                                                    <p>Kode Surat : {{ $letter->document->letter_code }}</p>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>{{ $letter->created_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a type="button" href="{{ route('letters.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-icon btn-sm" title="Hapus"
                                                onclick="confirmDelete({{ $letter->id }})">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                            <form id="delete-form-{{ $letter->id }}"
                                                action="{{ route('letters.destroy', ['letter' => $letter->id]) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <!-- Tab for Fully Validated Letters -->
                        <div class="tab-pane fade" id="validated-letters" role="tabpanel"
                            aria-labelledby="validated-letters-tab">
                            <h5 class="mt-3">Surat yang Sudah Divalidasi Sepenuhnya</h5>
                            @if($fullyValidatedLetters->isEmpty())
                            <div class="alert alert-info">
                                Tidak ada surat yang divalidasi sepenuhnya atau tidak memiliki kode surat.
                            </div>
                            @else
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;">No</th>
                                        <th>Title</th>
                                        <th>About</th>
                                        <th>Purpose</th>
                                        <th>Validation</th>
                                        <th>Date</th>
                                        <th class="disabled-sorting text-right" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr style="text-align: center;">
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>About</th>
                                        <th>Purpose</th>
                                        <th>Validation</th>
                                        <th>Date</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($fullyValidatedLetters as $key=>$letter)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $letter->title }}</td>
                                        <td>{{ $letter->about }}</td>
                                        <td>{{ $letter->purpose }}</td>
                                        <td>
                                            <ul>
                                                @foreach($letter->validators as $validator)
                                                <li>{{ $validator->name }}:
                                                    {{ $validator->pivot->is_validated ? 'Sudah' : 'Belum' }} -
                                                    <button class="btn btn-info btn-icon btn-sm" title="Catatan"
                                                        onclick="showNote('{{ $validator->name }}', '{{ $validator->pivot->notes }}')">
                                                        <i class="far fa-comments"></i>
                                                    </button>
                                                </li>
                                                <br>
                                                @endforeach
                                                <li>
                                                    <p>Kode Surat : {{ $letter->document->letter_code }}</p>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>{{ $letter->updated_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ asset('storage/' . $letter->document->file) }}" target="_blank"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm"
                                                title="Lihat Dokumen">
                                                <i class="far fa-file"></i>
                                            </a>
                                            <a type="button" href="{{ route('letters.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-icon btn-sm" title="Hapus"
                                                onclick="confirmDelete({{ $letter->id }})">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                            <form id="delete-form-{{ $letter->id }}"
                                                action="{{ route('letters.destroy', ['letter' => $letter->id]) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach()
                                </tbody>
                            </table>
                            @endif
                        </div>
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
            "targets": [4, 6]
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