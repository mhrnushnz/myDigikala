<div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
        <table id="zero-config" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>#</th>
                <th>  نام ادمین</th>
                <th>اطلاعات تماس</th>
                <th>نقش ادمین</th>
                <th>دسترسی ادمین</th>
                <th class="no-content"></th>
            </tr>
            </thead>
            <tbody>

            @foreach($admins as $admin)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>
                        {{ $admin->email }}
                        <br>
                        {{ $admin->mobile }}
                    </td>
                    <td>
                        @foreach($admin->roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </td>
                    <td>
                        @foreach($admin->roles as $role)
                            @foreach($role->permissions as $permission)
                                {{ $permission->name }}
                                <br>
                            @endforeach
                        @endforeach
                    </td>
                    <td><a wire:click="delete({{ $admin->id }})" wire:confirm="حذف شود؟" href="#">Delete</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

