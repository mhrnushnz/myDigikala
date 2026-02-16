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

        <div class="d-flex">
            <h3>سفارشات</h3>
            <input class="me-2" type="text" wire:model.live.debunce.300ms="search" placeholder="جستوجو کنید">
        </div>



        <table id="zero-config" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>تعداد</th>
                <th>کد سفارشات</th>
                <th>تاریخ</th>
                <th>کاربر</th>
                <th class="w-[150px]">مبلغ نهایی</th>
                <th class="w-[150px]">وضعیت</th>
                <th class="text-center w-[150px]">جزئیات</th>
            </tr>
            </thead>

            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td> {{ $loop->index + 1 }}  </td>
                    <td> {{ $order->order_number}}  </td>
                    <td>
                        {{jalali($order->created_at)->format('d m y | h:i')}}
                        <br>
                        {{$order->created_at->diffForHumans() }}
                    </td>
                    <td>
                        {{@$order->user->name}}
                        <br>
                        {{@$order->user->mobile}}
                        <br>
                        {{@$order->user->email}}
                    </td>
                    <td class="text-danger">{{number_format($order->amount)}}</td>
                    <td class="text-center">
                        @role('order admin')
                        <select class="form-control bg-{{$order->statusColor}}" wire:change="changeStatus({{$order->id}}, $event.target.value)">
                            <option value="pending"  {{$order->status == 'pending'? 'selected' : ''}}>pending</option>
                            <option value="processing " {{$order->status == 'processing'? 'selected' : ''}}>processing</option>
                            <option value="completed"  {{$order->status == 'completed'? 'selected'  : ''}}>completed</option>
                            <option value="cancel"  {{$order->status == 'cancel'? 'selected' : ''}}>cancel</option>
                        </select>
                        @endrole

                    </td>

                    <td><a class="bg-primary rounded-pill p-1 text-center" href="{{route('admin.order.details', $order->id) }}">جزئیات سفارش</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
