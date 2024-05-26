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
                    <h5 class="title">{{__(" Edit Letter")}}</h5>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <form method="POST" action="{{ route('letters.update', $letter->id) }}" id="myForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">{{__(" Title")}}</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter Title" value="{{ old('title', $letter->title) }}" required>
                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="about">{{__(" About")}}</label>
                                    <input type="text" class="form-control" id="about" name="about"
                                        placeholder="Enter About" value="{{ old('about', $letter->about) }}" required>
                                    @include('alerts.feedback', ['field' => 'about'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purpose">{{__(" Purpose")}}</label>
                                    <input type="text" class="form-control" id="purpose" name="purpose"
                                        placeholder="Enter Purpose" value="{{ old('purpose', $letter->purpose) }}"
                                        required>
                                    @include('alerts.feedback', ['field' => 'purpose'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="Letter File">{{__(" Letter File")}}</label>
                                @if ($letter->document && $letter->document->file)
                                <small class="form-text text-muted">Dokumen saat ini: <a
                                        href="{{ asset('storage/' . $letter->document->file) }}" target="_blank">Lihat
                                        dokumen</a></small>
                                @endif
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" id="file">
                                    <label class="input-group-text" for="file">Upload</label>
                                    @include('alerts.feedback', ['field' => 'file'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="validators">{{__(" Validators")}}</label>
                                    <select name="validators[]" id="validators" class="form-control select2bs4"
                                        multiple>
                                        @foreach($validators as $validator)
                                        <option value="{{ $validator->id }}"
                                            {{ in_array($validator->id, $letter->validators->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $validator->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'validators'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Letter Description</label>
                                    <textarea rows="4" cols="80" class="form-control" id="description"
                                        name="description"
                                        placeholder="Here can be letter description">{{ old('description', $letter->description) }}</textarea>
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Save')}}</button>
                            <a class="btn btn-secondary btn-round text-white pull-right"
                                href="{{ route('letters.index') }}">Back</a>
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