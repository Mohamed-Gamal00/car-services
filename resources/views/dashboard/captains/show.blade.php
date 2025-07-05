@extends('dashboard.index')
@section('title', ' البيانات')
@push('styles')
    <!-- Bootstrap datatable js -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">

@endpush
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item"> بيانات كابتن</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-xl-4">
            <div class="user-sidebar">
                <div class="card" style="background-color: #f8f9fa">
                    <div class="card-body p-1">

                        <div class="mt-n4 position-relative">
                            <div class="text-center">
                                <img src="{{ $captain->image_url }}" alt=""
                                     class="avatar-xl rounded-circle img-thumbnail">

                                <div class="mt-3">
                                    <h5 class="">{{$captain->name}}  {{$captain->last_name}}</h5>
                                    <div>
                                        <a href="#"
                                           class="text-muted m-1">{{$captain->status == 'available' ? 'متاح' : 'مشغول' }}</a>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="p-3 mt-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <div class="p-1">
                                        <h5 class="mb-1">{{count($completed_orders)}}</h5>
                                        <p class="text-muted mb-0">الطلبات المكتملة</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-1">
                                        <h5 class="mb-1">{{$captain->phone_number}}</h5>
                                        <p class="text-muted mb-0">رقم الجوال</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--                        <div>--}}
                        {{--                            <p class="text-center fw-bold">--}}
                        {{--                                <a href="{{ route('captain.rating', $captain->id) }}">التقييم</a>--}}
                        {{--                            </p>--}}
                        {{--                        </div>--}}
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div>
        </div>

        <div class="col-xl-8">
            <div class="tab-pane active" id="about" role="tabpanel">
                <div>

                    <div>
                        <h5 class="font-size-16 mb-4">الطلبات المكتملة</h5>
                        <div class="table-responsive mt-2">

                            <table
                                    class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                    id="datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bold">رقم الطلب</th>
                                    <th class="fw-bold">اسم العميل</th>
                                    <th class="fw-bold">تاريخ الطلب</th>
                                    <th class="fw-bold">مشاهدة</th>
                                </tr>
                                </thead>


                                <tbody>
                                @forelse ($completed_orders as $order)
                                    <tr data-id="5">
                                        <td data-field="id">{{ $order->number }}</td>
                                        <td>{{ $order->user->first_name }}</td>
                                        {{-- <td data-field="id">{{ $order->addresses->first()->first_name }} {{ $order->addresses->first()->last_name }}</td> --}}

                                        <td data-field="id">{{ $order->created_at}}</td>


                                        <td style="width: 2%;text-align-last: center;">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                               class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                                <i class="ion ion-md-eye"></i>
                                            </a>
                                        @empty
                                            <td colspan="10">
                                                لا يوجد طلبات لعرضها
                                            </td>
                                    </tr>
                                @endforelse
                            </table>
                            <!-- end table -->
                            {{ $completed_orders->withQueryString()->links() }}
                        </div>
                    </div>


                    <div>
                        <h5 class="font-size-16 mb-4">الطلبات الحالية</h5>
                        <div class="table-responsive mt-2">

                            <table
                                    class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                    id="datatabless">
                                <thead>
                                <tr>
                                    <th class="fw-bold">رقم الطلب</th>
                                    <th class="fw-bold">اسم العميل</th>
                                    <th class="fw-bold">تاريخ الطلب</th>
                                    <th class="fw-bold">مشاهدة</th>
                                </tr>
                                </thead>


                                <tbody>
                                <tr data-id="5">
                                    @if($current_order)
                                        <td data-field="id">{{ $current_order->number }}</td>
                                        <td>{{ $current_order->user->first_name }}</td>
                                        {{-- <td data-field="id">{{ $current_order->addresses->first()->first_name }} {{ $current_order->addresses->first()->last_name }}</td> --}}

                                        <td data-field="id">{{ $current_order->created_at}}</td>


                                        <td style="width: 2%;text-align-last: center;">
                                            <a href="{{ route('orders.show', $current_order->id) }}"
                                               class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                                <i class="ion ion-md-eye"></i>
                                            </a>
                                        </td>
                                    @else
                                        <td colspan="10">
                                            لا يوجد طلبات لعرضها
                                        </td>

                                    @endif
                                </tr>
                            </table>
                            <!-- end table -->
                        </div>
                    </div>


                    <div>
                        <h5 class="font-size-16 mb-4">قائمة الانتظار</h5>
                        <div class="table-responsive mt-2">

                            <table
                                    class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                    id="datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bold">رقم الطلب</th>
                                    <th class="fw-bold">اسم العميل</th>
                                    <th class="fw-bold">تاريخ الطلب</th>
                                    <th class="fw-bold">الطلب</th>
                                    <th class="fw-bold">مشاهدة</th>
                                </tr>
                                </thead>


                                <tbody>
                                @forelse ($wating_list as $order)
                                    <tr data-id="5">
                                        <td data-field="id">{{ $order->number }}</td>
                                        <td>{{ $order->user->first_name }}</td>
                                        {{-- <td data-field="id">{{ $order->addresses->first()->first_name }} {{ $order->addresses->first()->last_name }}</td> --}}

                                        <td data-field="id">{{ $order->created_at}}</td>
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
                                        </td>

                                        <td style="width: 2%;text-align-last: center;">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                               class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                                <i class="ion ion-md-eye"></i>
                                            </a>
                                        @empty
                                            <td colspan="10">
                                                لا يوجد طلبات لعرضها
                                            </td>
                                    </tr>
                                @endforelse
                            </table>
                            <!-- end table -->
                            {{ $wating_list->withQueryString()->links() }}
                        </div>
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