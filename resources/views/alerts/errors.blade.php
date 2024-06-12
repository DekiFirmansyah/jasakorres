@if (session($key ?? 'errors'))
<div id="status-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session($key ?? 'errors') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif