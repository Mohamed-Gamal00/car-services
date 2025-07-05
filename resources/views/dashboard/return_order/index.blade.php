@extends('dashboard.index')
@section('title', 'الطلبات الملغية')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الطلبات الملغية</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>

                    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mt-5">
                        <x-form.input type="text" name="order_number" placeholder="البحث عن طريق رقم الطلب..."
                                      class="mx-2" :value="request('order_number')"/>
                        <button class="btn btn-dark">بحث</button>
                    </form>


                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="country-table">
                            <thead>
                            <tr>
                                <th class="fw-bold">رقم الطلب</th>
                                <th class="fw-bold">اسم العميل</th>
                                <th class="fw-bold">تاريخ الغاء الطلب</th>
                                <th class="fw-bold">حالة الطلب</th>
                                <th class="fw-bold">حالة الدفع</th>
                                <th class="fw-bold">مشاهدة</th>
                                {{--                                <th class="fw-bold">حذف</th>--}}
                            </tr>
                            </thead>


                            <tbody>
                            @forelse ($orders as $order)
                                <tr data-id="5">
                                    <td data-field="id">{{ $order->number }}</td>

                                    <td>
                                        <a href="{{route('clients.edit',$order->user->id)}}">
                                            {{ $order->user->first_name }}
                                        </a>
                                    </td>

                                    <td data-field="id">{{ $order->updated_at}}</td>
                                    <td data-field="id" style="width: 8%;">


                                        @if ($order->order_status_id  == 4)
                                            <span class="badge bg-success"
                                                  style="font-size: 13px">{{ $order->orderStatus->name }}</span>
                                        @elseif($order->order_status_id  == 2 )

                                            <span class="badge bg-danger"
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

                                    @can('order.edit')
                                        <td style="width: 2%;text-align-last: center;">
                                            <a href="{{ route('return_orders.show', $order->id) }}"
                                               class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                                <i class="ion ion-md-eye"></i>
                                            </a>
                                    @endcan
                                    {{--                                            @can('order.delete')--}}
                                    {{--                                                <form method="post" id="formDelete_{{ $order->id }}"--}}
                                    {{--                                                      action="{{ route('orders.destroy', $order->id) }}">--}}
                                    {{--                                                    @csrf--}}
                                    {{--                                                    @method('delete')--}}
                                    {{--                                                    <td style="width: 5%">--}}
                                    {{--                                                        <button style="font-size: 12px;"--}}
                                    {{--                                                                class="btn btn-danger waves-effect waves-light"--}}
                                    {{--                                                                title="حذف"--}}
                                    {{--                                                                type="button" onclick="confirmDelete({{ $order->id }})">--}}
                                    {{--                                                            <i class="fas fa-trash-alt"></i>--}}
                                    {{--                                                        </button>--}}
                                    {{--                                                    </td>--}}
                                    {{--                                                </form>--}}
                                    {{--                                    @endcan--}}
                                    @empty
                                        <td colspan="10">
                                            لا يوجد طلبات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                        </table>
                        <!-- end table -->
                        {{ $orders->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
