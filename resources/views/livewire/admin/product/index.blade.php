<div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">

        @if(session()->has('success'))
            <div class="alert alert-icon-left alert-light-primary mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg  data-dismiss="alert"> ... </svg></button>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>

                <strong>هشدار!</strong>
                {{sesstion()->get('success')}}
            </div>
        @endif



        <table id="zero-config" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                <th>تعداد</th>
                <th>تصویر محصول</th>
                <th>نام محصول</th>
                <th>دسته بندی</th>
                <th>قیمت</th>
                <th> ویژگی ها</th>
                <th>محتوای محصول</th>
                <th class="no-content"></th>
                <th class="no-content"></th>
            </tr>
            </thead>

            <tbody>
                @foreach($products as $product)
                    <tr>
                    <td> {{ $loop->index + 1 }}  </td>
                    <td> <img src="/products/{{$product->id}}/small/{{@$product->coverImage->path}}" alt=""> </td>
                    <td> {{ $product->name }} </td>
                    <td> {{ $product->category?->name }} </td>
                    <td> {{ number_format($product->price )}} </td>
                    <td><a class="bg-primary rounded-pill p-1" href="{{ route('admin.product.features', ['product'=>$product])}}">ویژگی ها</a></td>
                    <td><a class="bg-primary rounded-pill p-1" href="{{ route('admin.product.content', $product->id)}}">محتوای محصول</a></td>
                    <td class="text-end hover:bg-green-600"><a href="{{ route('admin.product_create.index') }}?p_id={{$product->id}}">Edit</a></td>
                    <td class="text-start hover:bg-red-600"><a wire:click="delete({{$product->id}})" wire:confirm="آیا مطمئن هستید؟" href="#" class="hover:bg-danger-600">Delete</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
