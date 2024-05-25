@extends('layouts.app', [
'namePage' => 'Letters Management',
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
                    <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('letters.create') }}">Add
                        Letter</a>
                    <h4 class="card-title">Letters</h4>
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
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Title</th>
                                <th>About</th>
                                <th>Purpose</th>
                                <th>Validation</th>
                                <th>Create Date</th>
                                <th class="disabled-sorting text-right">Actions</th>
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
                                    </ul>
                                </td>
                                <td>{{ $letter->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <a type="button" href="{{ route('letters.edit', $letter->id) }}" rel="tooltip"
                                        class="btn btn-success btn-icon btn-sm" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-icon btn-sm" title="Delete"
                                        onclick="confirmDelete({{ $letter->id }})">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $letter->id }}"
                                        action="{{ route('letters.destroy', ['letter' => $letter->id]) }}" method="POST"
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
</script>
@endpush