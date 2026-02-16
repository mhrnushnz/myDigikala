<div class="row">
    <div class="row">
        @if(session('message'))
            <div class="alert alert-success w-full d-block">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="col-md-4 d-block">
        @include('livewire.admin.admin-user.form')
    </div>
    <div class="col-md-8">@include('livewire.admin.admin-user.list')</div>
</div>

