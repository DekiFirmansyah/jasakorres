@if (session($key ?? 'status'))
<div id="status-alert" class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session($key ?? 'status') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif