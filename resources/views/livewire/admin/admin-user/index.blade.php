<div>
    <div class="row">
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="col-md-4">
            @include('livewire.admin.admin-user.form')
        </div>

        <div class="col-md-8">
            @include('livewire.admin.admin-user.list')
        </div>
    </div>
</div>
