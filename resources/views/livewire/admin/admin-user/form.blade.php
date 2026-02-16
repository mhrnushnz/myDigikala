<div>
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-12"><h4>مدیریت ادمین ها</h4></div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <form wire:submit.prevent="submit">
                <div class="form-group mb-3">
                    <input wire:model="name" name="name" type="text" class="form-control" placeholder="نام ادمین">
                </div>
                @error('name')
                <div class="alert alert-danger mb-4" wire:loading.remove>{{ $message }}</div>
                @enderror




                <div class="form-group mb-3">
                    <input wire:model="email" name="email" type="text" class="form-control" placeholder="ایمیل ادمین">
                </div>
                @error('email')
                <div class="alert alert-danger mb-4" wire:loading.remove>{{ $message }}</div>
                @enderror




                <div class="form-group mb-3">
                    <input wire:model="mobile" name="mobile" type="text" class="form-control" placeholder="موبایل ادمین">
                </div>
                @error('mobile')
                <div class="alert alert-danger mb-4" wire:loading.remove>{{ $message }}</div>
                @enderror




                <div class="form-group mb-3">
                    <label for="selectedRoles">انتخاب نقش</label>
                    <select id="selectedRoles" name="selectedRoles" wire:model="selectedRoles" class="form-control" multiple>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('selectedRoles')
                <div class="alert alert-danger mb-4" wire:loading.remove>{{ $message }}</div>
                @enderror




                <div class="form-group mb-3">
                    <label for="selectedPermissions">انتخاب دسترسی</label>
                    <select id="selectedPermissions" name="selectedPermissions" wire:model="selectedPermissions" class="form-control" multiple>
                        @foreach($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('selectedPermissions')
                <div class="alert alert-danger mb-4" wire:loading.remove>{{ $message }}</div>
                @enderror




                <button type="submit" class="btn btn-info btn-lg mb-3 mr-3">
                    <div wire:loading class="spinner-border text-primary align-self-center"></div>
                    <span wire:loading.remove>ثبت</span>
                </button>
            </form>
        </div>
    </div>
</div>

