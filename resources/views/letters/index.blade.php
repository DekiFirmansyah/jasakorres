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
                            <table id="datatable-your-letters" class="table table-striped table-bordered"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Judul</th>
                                        <th>Perihal</th>
                                        <th>Dikirim Kepada</th>
                                        <th>Validator</th>
                                        <th>Tgl Buat</th>
                                        <th class="disabled-sorting text-right" style="width: 100px;">Aksi</th>
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
                                            <table class="table table-sm table-borderless">
                                                <thead>
                                                    <tr style="font-size: 12px; text-align: center;">
                                                        <th>Nama</th>
                                                        <th>Status Validasi</th>
                                                        <th>Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($letter->validations as $validator)
                                                    <tr style="text-align: center;">
                                                        <td>{{ $validator->user->name }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $validator->is_validated ? 'badge-success' : 'badge-danger' }}">
                                                                {{ $validator->is_validated ? 'Valid' : 'Belum Valid' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-icon btn-sm"
                                                                title="Riwayat Catatan"
                                                                onclick="showNoteHistory('{{ $letter->id }}', '{{ $validator->user->id }}')">
                                                                <i class="far fa-comments"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4">
                                                            <strong>Kode Surat:</strong>
                                                            <span
                                                                class="{{ $letter->document->letter_code ? '' : 'text-muted' }}">
                                                                {{ $letter->document->letter_code ?? 'Belum ada nomor surat' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>{{ $letter->created_at->format('d M Y') }}</td>
                                        <td class="text-right">
                                            <a type="button" href="{{ route('letters.show', $letter->id) }}"
                                                rel="tooltip" class="btn btn-info btn-icon btn-sm" title="Detail surat">
                                                <i class="fa fa-info"></i>
                                            </a>
                                            <a type="button" href="{{ route('letters.edit', $letter->id) }}"
                                                rel="tooltip" class="btn btn-success btn-icon btn-sm"
                                                title="Edit surat">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-icon btn-sm" title="Hapus surat"
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
                            <table id="datatable-validated-letters" class="table table-striped table-bordered"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Judul</th>
                                        <th>Perihal</th>
                                        <th>Dikirim Kepada</th>
                                        <th>Nomor Surat</th>
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
                                        <th>Nomor Surat</th>
                                        <th>Tgl Buat</th>
                                        <th>Aksi</th>
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
                                            {{ $letter->document->letter_code }}
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
    $('#datatable-your-letters').DataTable({
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

    $('#datatable-validated-letters').DataTable({
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

function showNoteHistory(letterId, validatorId) {
    fetch(`/letters/${letterId}/validator/${validatorId}/notes`)
        .then(response => response.json())
        .then(data => {
            let notesHtml = '<ul>';
            data.notes.forEach(note => {
                const noteText = note.notes ? note.notes : 'Tidak ada catatan';

                const date = new Date(note.created_at);
                const formattedDate = ('0' + date.getDate()).slice(-2) + '/' +
                    ('0' + (date.getMonth() + 1)).slice(-2) + '/' +
                    date.getFullYear();

                notesHtml += `<li>
                                    <strong>${note.user.name}:</strong> ${noteText} 
                                    <small>(${formattedDate})</small>
                                ${note.revised_file ? `<br><a href="/storage/${note.revised_file}" target="_blank" 
                                class="btn btn-info btn-round">Lihat File Revisi</a>` : ''}
                            </li>`;
            });
            notesHtml += '</ul>';

            Swal.fire({
                title: 'Riwayat Catatan',
                html: notesHtml,
                icon: 'info'
            });
        })
        .catch(error => {
            Swal.fire('Error', 'Tidak dapat mengambil data catatan.', 'error');
        });
}
</script>
@endpush