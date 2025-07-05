@extends('dashboard.index')
@section('title', 'العملاء')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('clients.index')}}">العملاء</a></li>
    <li class="breadcrumb-item">طلبات العميل {{$client->first_name}}</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="country-table">
                            <thead>
                            <tr>
                                <th class="fw-bold">رقم الطلب</th>
                                <th class="fw-bold">اسم العميل</th>
                                <th class="fw-bold">تاريخ الطلب</th>
                                <th class="fw-bold">حالة الطلب</th>
                                <th class="fw-bold">حالة الدفع</th>
                                <th class="fw-bold">تفاصيل الطلب</th>
                            </tr>
                            </thead>


                            <tbody>
                            @forelse ($client->orders as $order)
                                <tr data-id="5">
                                    <td data-field="id">{{ $order->number }}</td>
                                    <td>{{ $order->user->first_name. ' ' .$order->user->family_name }}</td>


                                    <td data-field="id">{{ $order->created_at}}</td>

                                    {{-- set default order status if there'a no order status --}}
                                    <td data-field="id" style="width: 8%;">


                                        @if ($order->order_status_id  == 4)
                                            <span class="badge bg-success"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}</span>
                                        @elseif($order->order_status_id  == 2 )
                                            <span class="badge bg-warning"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}
                                            </span>
                                        @elseif($order->order_status_id  == 3 )
                                            <span class="badge bg-purple"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}
                                            </span>
                                        @elseif($order->order_status_id  == 11 )
                                            <span class="badge bg-danger"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}
                                            </span>

                                        @elseif($order->order_status_id  == 12 )
                                            <span class="badge bg-primary"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}
                                            </span>
                                        @else
                                            ---
                                        @endif
                                        {{-- Check if updated by admin --}}
                                        @if ($order->updated_by_admin)
                                            <span class="text-muted" style="font-size: 11px;">(مسئول)</span>
                                        @endif
                                    </td>
                                    <td style="width: 2%;text-align-last: center;">
                                        @if($order->payment_status == 'paid')
                                            <span class="text-success">مدفوع</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="text-warning">غير مدفوع</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="text-danger">فاشلة</span>
                                        @endif
                                    </td>
                                    <td style="width: 2%;text-align-last: center;">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                            <i class="ion ion-md-eye"></i>
                                        </a>
                                    </td>
                                    @empty
                                        <td colspan="10">
                                            لا يوجد طلبات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                        </table>

                        {{ $client->orders->withQueryString()->links() }}

                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
