@extends('dashboard.index')
@section('title', 'المدفوعات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">المدفوعات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{--                    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mt-5">--}}
                    {{--                        <x-form.input type="text" name="order_number" placeholder="البحث عن طريق رقم الطلب..."--}}
                    {{--                                      class="mx-2" :value="request('order_number')"/>--}}
                    {{--                        <button class="btn btn-dark">بحث</button>--}}
                    {{--                    </form>--}}

                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="country-table">
                            <thead>
                            <tr>
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">رقم الطلب</th>
                                <th class="fw-bold">رقم الاشتراك في الباقة</th>
                                <th class="fw-bold">العميل</th>
                                <th class="fw-bold">الحالة</th>
                                {{--                                <th class="fw-bold">الوصف</th>--}}
                                <th class="fw-bold">الاجمالي</th>
                                <th class="fw-bold">البطاقة المستخدمة</th>
                                <th class="fw-bold">رقم العملية</th>
                                <th class="fw-bold">العملة</th>
                                <th class="fw-bold">تاريخ العملية</th>
                            </tr>
                            </thead>


                            <tbody>
                            @forelse($payments  as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->order_number }}</td>
                                    <td>{{ $payment->package_reference }}</td>


                                    <td>
                                        <a href="{{route('clients.edit',$payment->user_id)}}">
                                            {{ $payment->user_name }}
                                        </a>
                                    </td>
                                    <td>

                                        @if($payment->status == 'paid')
                                            <span class="text-success">مدفوع</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="text-warning">غير مدفوع</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="text-danger">فاشلة</span>
                                    @endif
                                    {{--                                    <td>{{ $payment->description }}</td>--}}
                                    <td>{{ number_format($payment->amount / 100, 2) }}</td>

                                    <td>{{ $payment->source }}</td>
                                    <td>{{ $payment->payment_id }}</td>
                                    <td>{{ $payment->cur }}</td>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        لا يوجد بيانات لعرضها
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                        <!-- end table -->
                        {{--                        {{ $payments->links() }}--}}
                        {{ $payments->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
