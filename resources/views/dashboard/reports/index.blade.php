@extends('dashboard.index')

@push('styles')
    <!-- Bootstrap datatable js -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">

@endpush

@section('title', 'التقارير')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page"> التقارير</li>
@endsection

@section('section')
    <x-alert type="success"/>

    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#home2" role="tab">
                    <span class="d-none d-md-block">تقارير عامة </span><span class="d-block d-md-none"><i
                                class="mdi mdi-home-variant h5"></i></span>
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <x-alert type='danger'/>
            {{-- التقارير العامة --}}
            <div class="tab-pane active p-3" id="home2" role="tabpanel">
                <div class="row">
                    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div data-repeater-list="group-a">
                                        <div class="row" data-repeater-item>
                                            <h5 class="card-title mb-3 mt-2">جميع التقارير</h5>
                                            <div class="col-sm-4">
                                                <select name="report_type" class="form-select country-select">
                                                    <option value=""></option>
                                                    @foreach ($reportTitle as $key => $val)
                                                        <option value="{{ $key }}"
                                                                {{ request('report_type') == $key ? 'selected' : '' }}>
                                                            {{ $val }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('report_type')
                                                <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3">
                                                <div style="width: 100%" class="input-group" id="datepicker1">
                                                    <input type="text" name="start_at" class="form-control"
                                                           placeholder="تاريخ البداية" data-date-format="yyyy-mm-dd"
                                                           data-date-container='#datepicker1'
                                                           data-provide="datepicker"
                                                           data-date-autoclose="true"
                                                           value="{{ request('start_at') }}">

                                                    <div>
                                                        @error('start_at')
                                                        <span class="error">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div style="width: 100%" class="input-group" id="datepicker2">
                                                    <input type="text" name="end_at" class="form-control"
                                                           placeholder="تاريخ النهاية" data-date-format="yyyy-mm-dd"
                                                           data-date-container='#datepicker2'
                                                           data-provide="datepicker"
                                                           data-date-autoclose="true"
                                                           value="{{ request('end_at') }}"
                                                    >
                                                </div>
                                                @error('end_at')
                                                <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-dark fw-bold">عرض</button>
                                            </div>


                                            {{--                                            @if(isset($reportType))--}}
                                            {{--                                                @switch($reportType)--}}
                                            {{--                                                    @case('orders-report')--}}
                                            {{--                                                        <select name="order_status_id" class="form-control mx-2">--}}
                                            {{--                                                            <option value="">كل الحالات</option>--}}
                                            {{--                                                            @forelse($OrderStatus as $status)--}}
                                            {{--                                                                <option value="{{ $status->id }}" @selected(request('order_status_id') == $status->id)>{{$status->name}}</option>--}}
                                            {{--                                                            @empty--}}
                                            {{--                                                                <option disabled>لا توجد حالة</option>--}}
                                            {{--                                                            @endforelse--}}
                                            {{--                                                        </select>--}}
                                            {{--                                                @endswitch--}}
                                            {{--                                            @endif--}}

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(isset($reportType))
            @switch($reportType)
                @case('orders-report')
                    <div class="container">
                        @if(count($orders) > 0)
                            <a style="position: absolute;" href="{{ route('orders.export', request()->query()) }}"
                               class="btn btn-success mb-2">
                                <i class="feather icon-download mr-1"></i> تصدير إلى إكسل
                            </a>
                        @endif

                        <div style="overflow-x: auto; width: 100%;">
                            <table id="datatable" class="table r table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="fw-bold">اسم العميل</th>
                                    <th class="fw-bold">اسم الخدمة</th>
                                    <th class="fw-bold">سعر الخدمة</th>
                                    <th class="fw-bold">الخدمات الاضافية</th>
                                    <th class="fw-bold"> سعر الخدمات الاضافية</th>

                                    {{--                                <th class="fw-bold">البريد الالكتروني</th>--}}
                                    {{--                                <th class="fw-bold">الدولة</th>--}}
                                    {{--                                <th class="fw-bold">المدينة</th>--}}
                                    <th class="fw-bold">تاريخ الطلب</th>
                                    <th class="fw-bold">الكوبون</th>
                                    <th class="fw-bold">الخصم</th>
                                    <th class="fw-bold">المدفوع</th>
                                    {{--                                <th class="fw-bold">مشاهدة</th>--}}
                                    {{--                                <th class="fw-bold">حذف</th>--}}
                                </tr>
                                </thead>


                                <tbody>
                                @forelse ($orders as $order)
                                    <tr data-id="5">
                                        <td>
                                            @if($order->user->id)
                                                <a href="{{route('clients.edit',$order->user->id)}}">
                                                    {{ $order->user->first_name .' '. $order->user->family_name}}
                                                </a>
                                            @endif

                                        </td>

                                        <td data-field="id">{{ $order->products->first()->name ?? 'لا توجد خدمة' }}</td>
                                        <td data-field="id"> {{ $order->products->first()->price  ?? 'لا توجد سعر' }}
                                            ريال
                                        </td>
                                        <td data-field="id">
                                            @forelse($order->choices as $choices)
                                                <div class="feed-item-list">
                                                    <span class=" fw-bold">{{ $choices->name }}    </span>
                                                </div>
                                            @empty
                                                <p></p>
                                            @endforelse
                                        </td>

                                        <td data-field="id">            @forelse($order->choices as $choices)
                                                <div class="feed-item-list">
                                                    <span class=" fw-bold">{{$choices->service_price}}  ريال  </span>
                                                </div>
                                            @empty
                                                <p></p>
                                            @endforelse
                                        </td>
                                        {{-- <td data-field="id">{{ $order->addresses->first()->first_name }} {{ $order->addresses->first()->last_name }}</td> --}}

                                        {{--                                    <td data-field="id">{{ $order->user->id ? $order->user->email : 'زائر' }}</td>--}}
                                        {{--                                    <td data-field="id">{{ $order->addresses->first()->country->name_ar ?? '-' }}</td>--}}
                                        {{--                                    <td data-field="id">{{ $order->addresses->first()->city->name_ar ?? '-' }}</td>--}}

                                        <td data-field="id">{{ $order->created_at}}</td>

                                        {{-- set default order status if there'a no order status --}}
                                        {{--                                    <td data-field="id" style="width: 8%;">--}}


                                        {{--                                        @if ($order->order_status_id  == 4)--}}
                                        {{--                                            <span class="badge bg-success"--}}
                                        {{--                                                  style="font-size: 13px">{{ $order->orderStatus->name }}</span>--}}
                                        {{--                                        @elseif($order->order_status_id  == 2 )--}}
                                        {{--                                            <span class="badge bg-primary"--}}
                                        {{--                                                  style="font-size: 13px">{{ $order->orderStatus->name }}--}}
                                        {{--                                            </span>--}}
                                        {{--                                        @elseif($order->order_status_id  == 3 )--}}
                                        {{--                                            <span class="badge bg-warning"--}}
                                        {{--                                                  style="font-size: 13px">{{ $order->orderStatus->name }}--}}
                                        {{--                                            </span>--}}
                                        {{--                                        @elseif($order->order_status_id  == 11 )--}}
                                        {{--                                            <span class="badge bg-danger"--}}
                                        {{--                                                  style="font-size: 13px">{{ $order->orderStatus->name }}--}}
                                        {{--                                            </span>--}}
                                        {{--                                        @else--}}
                                        {{--                                            -----}}
                                        {{--                                        @endif--}}
                                        {{--                                        --}}{{-- Check if updated by admin --}}
                                        {{--                                        @if ($order->updated_by_admin)--}}
                                        {{--                                            <span class="text-muted" style="font-size: 11px;">(مسئول)</span>--}}
                                        {{--                                        @endif--}}
                                        {{--                                    </td>--}}
                                        <td style="width: 2%;text-align-last: center;">
                                            <span class="text-success">{{$order->discount_applied}}</span>
                                        </td>
                                        <td style="width: 2%;text-align-last: center;">
                                        <span class="">{{$order->discount_applied ?($order->totalBeforeDiscount - $order->total_price ).' '.'ريال':  ''}}

                                        </span>

                                        </td>
                                        <td style="width: 2%;text-align-last: center;">
                                        <span class="">{{$order->total_price}}
                                        ريال
                                        </span>

                                        </td>


                                        {{--                                    <td style="width: 2%;text-align-last: center;">--}}
                                        {{--                                        <a href="{{ route('orders.show', $order->id)}}"--}}
                                        {{--                                           class="btn btn-secondary btn-sm edit" title="مشاهدة">--}}
                                        {{--                                            <i class="ion ion-md-eye"></i>--}}
                                        {{--                                        </a>--}}

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
                        </div>
                        {{ $orders->withQueryString()->links() }}


                    </div>
                    @break

                @case('customers-report')
                    <div class="container">
                        @if(count($clients) > 0)
                            <a style="position: absolute;" href="{{ route('clients.export', request()->query()) }}"
                               class="btn btn-success mb-2">
                                <i class="feather icon-download mr-1"></i> تصدير إلى إكسل
                            </a>

                        @endif
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                            <tr>
                                <th>اسم العميل</th>
                                <th>رقم الجوال</th>
                                <th>تاريخ الانشاء</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($clients as $client)
                                <tr data-id="5">

                                    <td data-field="name">{{ $client->first_name . ' ' . $client->family_name }}</td>
                                    <td data-field="phone_number">{{ $client->phone_number }}
                                        {{--                            {{ $client->addresses->first()->country->phone_code ?? '' }}+--}}
                                    </td>
                                    <td data-field="gender">{{ $client->created_at->format('Y-m-d H:i') }}</td>

                                    @empty
                                        <td colspan="6" class="text-center">
                                            لا يوجد عملاء لعرضهم
                                        </td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                        {{ $clients->withQueryString()->links() }}

                    </div>
                    @break

                @case('coupons-report')
                    <div class="container">
                        @if(count($discounts) > 0)
                            <a style="position: absolute;" href="{{ route('coupons.export', request()->query()) }}"
                               class="btn btn-success mb-2">
                                <i class="feather icon-download mr-1"></i> تصدير إلى إكسل
                            </a>
                        @endif
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                            <tr>
                                <th>اسم كود الخصم</th>
                                <th>الكود</th>
                                <th>الخصم</th>
                                <th>عدد مرات الاستخدام المتاحه</th>
                                <th>الحالة</th>

                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($discounts as $code)
                                <tr data-id="5">
                                    <td data-field="id">{{ $code->name }}</td>
                                    <td data-field="id">{{ $code->code }}</td>
                                    <td data-field="id">{{ $code->price }}</td>
                                    <td data-field="id">{{ $code->number_of_used }}</td>
                                    <td data-field="id">{{ $code->status == 'active' ? 'نشط' : 'غير نشط' }}</td>


                                    @empty
                                        <td colspan="6" class="text center">
                                            لا يوجد بيانات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{ $discounts->withQueryString()->links() }}
                    </div>
                    @break
            @endswitch
        @else
            <p class="text-center text-primary">يرجى اختيار تقرير لعرض البيانات.</p>
        @endif


    </div>
@endsection

@section('scripts')

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
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

@endsection
