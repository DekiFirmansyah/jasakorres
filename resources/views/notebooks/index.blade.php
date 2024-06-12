@extends('layouts.app', [
'namePage' => 'Manajemen Agenda Surat',
'class' => 'sidebar-mini',
'activePage' => 'notebooks',
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
                        href="{{ route('notebooks.create') }}">Tambah
                        Agenda Surat</a>
                    <h4 class="card-title">Agenda Surat</h4>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!-- Filter Form -->
                        <form action="{{ route('notebooks.export_pdf') }}" method="GET" class="form-inline mb-3">
                            <div class="col-md-6 pr-1">
                                <h5>Cetak Laporan Agenda Surat</h5>
                                <div>
                                    <div class="form-group">
                                        <label for="month" class="mr-2">Pilih Bulan</label>
                                        <input type="month" class="form-control" id="month" name="month" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-round text-white">Export
                                        PDF</button>
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('export-excel-form').submit();"
                                        class="btn btn-success btn-round text-white">Export Excel</a>
                                </div>
                            </div>
                        </form>
                        <!-- Form untuk Export Excel -->
                        <form id="export-excel-form" action="{{ route('notebooks.export_excel') }}" method="GET"
                            style="display: none;">
                            <input type="hidden" id="month_hidden" name="month" required>
                        </form>
                        @include('alerts.success')
                        @include('alerts.errors')
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    @if($notebooks->isEmpty())
                    <div class="alert alert-info">
                        Anda belum membuat agenda surat apapun.
                    </div>
                    @else
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>No. Surat/Tgl Surat</th>
                                <th>Perihal Surat</th>
                                <th>Kepada</th>
                                <th>Alamat Tujuan</th>
                                <th>Tgl di Kirim</th>
                                <th class="disabled-sorting text-right" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr style="text-align: center;">
                                <th>No</th>
                                <th>No. Surat/Tgl Surat</th>
                                <th>Perihal surat</th>
                                <th>Kepada</th>
                                <th>Alamat Tujuan</th>
                                <th>Tgl di Kirim</th>
                                <th class="disabled-sorting text-right">Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($notebooks as $key=>$notebook)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $notebook->letter->document->letter_code }} /
                                    {{ $notebook->letter->updated_at->format('d M Y') }}</td>
                                <td>{{ $notebook->letter->title }}</td>
                                <td>{{ $notebook->destination_name }}</td>
                                <td>{{ $notebook->destination_address }}</td>
                                <td>{{ $notebook->date_sent->format('d M Y') }}</td>
                                <td class="text-right">
                                    <a type="button" href="{{ route('notebooks.edit', $notebook->id) }}" rel="tooltip"
                                        class="btn btn-success btn-icon btn-sm" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-icon btn-sm" title="Delete"
                                        onclick="confirmDelete({{ $notebook->id }})">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $notebook->id }}"
                                        action="{{ route('notebooks.destroy', ['notebook' => $notebook->id]) }}"
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
document.getElementById('month').addEventListener('change', function() {
    document.getElementById('month_hidden').value = this.value;
});
</script>
@endpush