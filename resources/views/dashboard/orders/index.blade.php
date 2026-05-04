@extends('dashboard.index')
@section('title', 'الطلبات')
@push('styles')
    <!-- Bootstrap datatable js -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">

@endpush

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الطلبات</li>
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

                        <select name="order_status_id" class="form-control mx-2">
                            <option value="">كل الحالات</option>
                            @forelse($OrderStatus as $status)
                                <option value="{{ $status->id }}" @selected(request('order_status_id') == $status->id)>{{$status->name}}</option>
                            @empty
                                <option disabled>لا توجد حالة</option>
                            @endforelse
                        </select>
                        <button class="btn btn-dark">بحث</button>
                    </form>

                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                        >
                            <thead>
                            <tr>
                                <th class="fw-bold">رقم الطلب</th>
                                <th class="fw-bold">اسم العميل</th>
                                {{--                                <th class="fw-bold">البريد الالكتروني</th>--}}
                                {{--                                <th class="fw-bold">الدولة</th>--}}
                                {{--                                <th class="fw-bold">المدينة</th>--}}
                                <th class="fw-bold">تاريخ الطلب</th>
                                <th class="fw-bold">حالة الطلب</th>
                                <th class="fw-bold">حالة الدفع</th>
                                <th class="fw-bold">مشاهدة</th>
                                <th class="fw-bold">حذف</th>
                            </tr>
                            </thead>


                            <tbody>
                            @forelse ($orders as $order)
                                <tr data-id="5">
                                    <td data-field="id">{{ $order->number }}</td>
                                    <td>
                                        @if($order->user)
                                            <a href="{{route('clients.edit',$order->user->id)}}">
                                                {{ $order->user->name}}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif

                                    </td>
                                    {{-- <td data-field="id">{{ $order->addresses->first()->first_name }} {{ $order->addresses->first()->last_name }}</td> --}}

                                    {{--                                    <td data-field="id">{{ $order->user->id ? $order->user->email : 'زائر' }}</td>--}}
                                    {{--                                    <td data-field="id">{{ $order->addresses->first()->country->name_ar ?? '-' }}</td>--}}
                                    {{--                                    <td data-field="id">{{ $order->addresses->first()->city->name_ar ?? '-' }}</td>--}}

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
                                            <span class="badge bg-indigo"
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
                                        <a href="{{ route('orders.show', $order->id)}}"
                                           class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                            <i class="ion ion-md-eye"></i>
                                        </a>

                                        @can('order.delete')
                                            <form method="post" id="formDelete_{{ $order->id }}"
                                                  action="{{ route('orders.destroy', $order->id) }}">
                                                @csrf
                                                @method('delete')
                                                <td style="width: 5%">
                                                    <button style="font-size: 12px;"
                                                            class="btn btn-danger waves-effect waves-light"
                                                            title="حذف"
                                                            type="button" onclick="confirmDelete({{ $order->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </form>
                                    @endcan
                                    @empty
                                        <td colspan="10">
                                            لا يوجد طلبات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                        </table>
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                paging: false, // Disable DataTables pagination
                searching: true, // Enable searching
                ordering: true, // Enable column ordering
                info: false, // Disable DataTables' "Showing X to Y of Z entries"
                order: [[0, 'desc']], // Order by the first column (created_at) in descending order
                columnDefs: [
                    {
                        targets: 0, // The column index for "created_at"
                        type: 'date' // Ensure it recognizes the date format for proper sorting
                    }
                ]
            });
        });
    </script>

    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Bootstrap datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endpush