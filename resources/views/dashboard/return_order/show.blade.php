@extends('dashboard.index')

@section('title', 'الطلبات الملغية ')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('return_orders.index')}}">الطلبات الملغية</a></li>
    <li class="breadcrumb-item"> طلب ملغي</li>
@endsection

@section('section')

    {{--    <div class="row font-size-20">--}}
    {{--        اسم العميل--}}
    {{--        : {{ $order->user->first()->first_name . ' ' . $order->user->first()->family_name }}--}}

    {{--        - رقم الطلب هو : {{$order->number . '#' }}--}}
    {{--    </div>--}}

    <div class="row">
        @forelse($order->orderItems as $item)
            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row font-size-20 mb-3">
                            اسم العميل
                            :

                            {{ $order->user->first_name . ' ' . $order->user->family_name }}

                            - رقم الطلب هو : {{$order->number . '#' }}
                        </div>
                        {{--                        <h4 class="card-title mb-4">اسم الخدمة :{{$item->product->name}}</h4>--}}
                        <ol class="activity-feed">
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date fw-bold">  اسم السيارة  :  {{ $order->car->current_name_lang }}</span>
                                </div>
                            </li>
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date fw-bold">نوع السيارة : {{ $order->car_model }}</span>

                                </div>
                            </li>

                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date fw-bold">التاريخ : {{ $order->booking_date }}</span>
                                    <span class="date fw-bold">الوقت : {{ $order->booking_time }}</span>

                                </div>
                            </li>

                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date fw-bold">اسم الخدمة : {{$order->products->first()->name }}</span>

                                </div>
                            </li>

                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date fw-bold">سعر الخدمة : {{$order->products->first()->price }}</span>
                                </div>
                            </li>


                            @forelse($order->choices as $choices)
                                <li class="feed-item">
                                    <div class="feed-item-list">
                                        <span class=" fw-bold">خدمة اضافية :  {{ $choices->name }}    </span>
                                        <span class=" fw-bold">السعر  :  {{$choices->service_price}}    </span>
                                    </div>
                                </li>
                            @empty
                                <p></p>
                            @endforelse

                            @forelse($order->images as $image)
                                <span>
                                <img src="{{ asset('storage/' . $image->image) }} " width="50px" height="50px"
                                     alt="Order Image">
                            </span>
                            @empty
                                <p></p>
                            @endforelse


                        </ol>

                    </div>
                </div>
            </div>
        @empty
        @endforelse

    </div>


    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">الفاتوره</h4>
                    <ol class="activity-feed">
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">اسم العميل : </span>
                                <span
                                        class="activity-text fw-bold">{{ $order->user->first_name . ' ' . $order->user->family_name }}</span>
                            </div>
                        </li>

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">رقم الجوال: </span>
                                <span class="activity-text fw-bold">{{ "{$order->user->phone_number}" }}
                            </div>
                        </li>

                        {{--                        <li class="feed-item">--}}
                        {{--                            <div class="feed-item-list">--}}
                        {{--                                <span class="date">البريد الالكتروني</span>--}}
                        {{--                                <span class="activity-text fw-bold">{{ $order->addresses->first()->email }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}


                        {{--                        <li class="feed-item">--}}
                        {{--                            <div class="feed-item-list">--}}
                        {{--                                <span class="date">الدولة</span>--}}
                        {{--                                <span--}}
                        {{--                                        class="activity-text fw-bold">{{ $order->addresses->first()->country->name_ar }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}

                        {{--                        <li class="feed-item">--}}
                        {{--                            <div class="feed-item-list">--}}
                        {{--                                <span class="date">المدينة</span>--}}
                        {{--                                <span class="activity-text fw-bold">{{ $order->addresses->first()->city->name_ar }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}

                        {{--                        <li class="feed-item">--}}
                        {{--                            <div class="feed-item-list">--}}
                        {{--                                <span class="date">العنوان</span>--}}
                        {{--                                <span class="activity-text fw-bold">{{ $order->addresses->first()->address }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">تكلفة الخدمة</span>
                                <span
                                        class="activity-text fw-bold">{{ $order->total_price }}</span>
                            </div>
                        </li>

                        {{--                        <li class="feed-item">--}}
                        {{--                            <div class="feed-item-list">--}}
                        {{--                                <span class="date">ملاحظات</span>--}}
                        {{--                                <span class="activity-text fw-bold">{{ $order->note ?? 'لا يوجد ملاحظات' }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}

                    </ol>
                    {{--                    @if ($order->shipping_price)--}}
                    {{--                        <h5>--}}
                    {{--                            الاجمالي :--}}
                    {{--                            --}}{{-- <span>{{ resolve('App\currency\Currency')->getCurrency($order->orderItems->sum('price')) }}</span> --}}
                    {{--                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price + intval($order->shipping_price)) }}</span>--}}
                    {{--                        </h5>--}}
                    {{--                    @else--}}
                    {{--                        <h5>--}}
                    {{--                            الاجمالي :--}}
                    {{--                            --}}{{-- <span>{{ resolve('App\currency\Currency')->getCurrency($order->orderItems->sum('price')) }} قبل الخصك</span> --}}
                    {{--                            <span>{{ resolve('App\currency\Currency')->getCurrency($order->total_price) }}</span>--}}
                    {{--                        </h5>--}}
                    {{--                        </h5>--}}
                    {{--                    @endif--}}

                </div>
            </div>
        </div>
    </div>

    {{--    <form action="{{route('orders.update', $order->id)}}" method="post">--}}
    {{--        @csrf--}}
    {{--        @method('put')--}}
    {{--        <td class="cart-tr content-block"--}}
    {{--            valign="top">--}}
    {{--            <div class="col-md-9">--}}
    {{--                <label for="validationCustom04" class="form-label fw-bold">تغيير حالة--}}
    {{--                    الطلب</label>--}}
    {{--                <select class="form-select" name="order_status_id"--}}
    {{--                        id="validationCustom04" required>--}}
    {{--                    @forelse($orderStatus as $status)--}}
    {{--                        <option--}}
    {{--                                value="{{$status->id}}" @selected($order->order_status_id == $status->id)>{{$status->name}}</option>--}}
    {{--                    @empty--}}
    {{--                    @endforelse--}}
    {{--                </select>--}}
    {{--                <div class="invalid-feedback">--}}
    {{--                    Please select a valid state.--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <button class="btn btn-primary mt-5" type="submit">حفظ الحالة--}}
    {{--            </button>--}}
    {{--        </td>--}}

    {{--            </form>--}}
    <!-- end row -->
@endsection
