@extends('dashboard.index')

@section('title', 'مشاهدة الطلب')
@section('css')
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
@endsection
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">الطلبات</a></li>
    <li class="breadcrumb-item">مشاهدة الطلب</li>
@endsection

@section('section')

    <div class="row font-size-20">
        اسم العميل
        : {{ $order->user->name }}

        - {{ __('profile.ORDER_NUMBER') }} : {{ $order->number . '#' }}
    </div>




    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <ol class="activity-feed">
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date fw-bold">  اسم السيارة  :  {{ $order->car ? $order->car->current_name : 'غير محدد' }}</span>
                            </div>
                        </li>
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date fw-bold">نوع السيارة : {{ $order->car_model ?? 'غير محدد' }}</span>
                            </div>
                        </li>
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date fw-bold">رقم السيارة : {{ $order->car_number ?? 'غير محدد' }}</span>
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
                                    <span class="date fw-bold">
                                        اسم الخدمة/الباقة :
                                        {{ $order->service?->getCurrentNameAttribute()
                                            ?? $order->userPackage?->package?->getCurrentNameAttribute()
                                            ?? 'غير محدد' }}
                                    </span>
                            </div>
                        </li>


                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date fw-bold">
                                    وقت الخدمة/الصلاحية : 
                                    {{ $order->service?->duration 
                                        ?? ($order->userPackage?->package?->validity_days ? $order->userPackage->package->validity_days . ' يوم' : 'غير محدد') }}
                                </span>
                            </div>
                        </li>

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date fw-bold">
                                    سعر الخدمة/الباقة : 
                                    {{ $order->service?->price 
                                        ?? $order->userPackage?->package?->price 
                                        ?? 'غير محدد' }}
                                </span>
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
    </div>

    {{-- العنوان --}}
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    @if( $order->latitude && $order->longitude)
                        <h4 class="card-title mb-4">العنوان</h4>
                        <ol class="activity-feed">
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="activity-text fw-bold">{{ $order->address }}</span>
                                </div>
                            </li>
                            <div style="height: 400px;" id="map"></div>
                        </ol>
                    @else
                        <p>-</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($order->latitude && $order->longitude)
        <script>
            function initMap() {
                var location = {lat: {{ $order->latitude }}, lng: {{ $order->longitude }}};
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: location
                });

                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'موقع الطلب'
                });
            }
        </script>

        <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuK5dAqp6_Fs7d58Qs-5SvJeyHECUJAoM&callback=initMap"
                async defer>
        </script>
    @endif



    @if($order->rating && $order->rating->stars)
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">التقييم</h4>
                        <ol class="activity-feed">
                            <div class="rating-star">
                                <input type="hidden" value="{{ $order->rating->stars ?? ''}}" class="rating"
                                       data-filled="mdi mdi-star text-warning"
                                       data-empty="mdi mdi-star-outline text-muted" data-readonly/>
                            </div>


                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    @if($order->captain)
                        <h4 class="card-title mb-4">بيانات الكابتن</h4>
                        <ol class="activity-feed">
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date">اسم الكابتن : </span>
                                    <a href="{{route('captains.show',$order->captain->id)}}">

                                        <span

                                                class="activity-text fw-bold">{{ $order->captain->name . ' ' . $order->captain->last_name  }}</span>
                                    </a>

                                </div>
                            </li>

                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date">رقم الجوال: </span>
                                    <span class="activity-text fw-bold">{{ "{$order->captain->phone_number}" }}
                                </div>
                            </li>

                            @else
                                <p>لا يوجد كابتن حاليا يرجي انتظار </p>

                            @endif

                        </ol>
                </div>
            </div>
        </div>
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
                                        class="activity-text fw-bold">{{ $order->user->name }}</span>
                            </div>
                        </li>

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">رقم الجوال: </span>
                                <span class="activity-text fw-bold">{{ "{$order->user->phone}" }}
                            </div>
                        </li>

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">تكلفة الخدمة</span>
                                <span
                                        class="activity-text fw-bold">{{ $order->total_price }}</span>
                            </div>
                        </li>

                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date">الفاتورة</span>
                                @if($order->invoice_url)
                                    <a href="{{ asset('storage/' . $order->invoice_url) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success">
                                        <i class="fas fa-file-pdf"></i> تحميل الفاتورة
                                    </a>
                                @else
                                    <span class="text-muted">لم يتم إنشاء الفاتورة بعد</span>
                                    @if($order->payment_status == 'paid')
                                        <form action="{{ route('orders.regenerate-invoice', $order->id) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-sync"></i> إنشاء الفاتورة
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>


    @if($order->payment_status == 'paid')
        <form class="mb-5" action="{{ route('orders.assignCaptain', $order->id) }}" method="post">
            @csrf
            @method('put')
            <td class="cart-tr content-block" valign="top">
                <div class="col-md-9">
                    <label for="validationCustom04" class="form-label fw-bold">تعيين كابتن للطلب</label>
                    <select class="form-select" name="captain_id" id="validationCustom04" required>
                        @forelse($availableCaptains as $captain)
                            <option value="{{ $captain->id }}" @selected($order->captain_id == $captain->id)>{{ $captain->name .''.  $captain->last_name }}
                            </option>
                        @empty
                            <option>لا يوجد كابتن متاح</option>
                        @endforelse
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid state.
                    </div>
                </div>
                <button class="btn btn-primary mt-2" type="submit">تسجيل كابتن
                </button>
            </td>

        </form>
    @endif


    <form action="{{ route('orders.update', $order->id) }}" method="post">
        @csrf
        @method('put')
        <td class="cart-tr content-block" valign="top">
            <div class="col-md-9">
                <label for="validationCustom04" class="form-label fw-bold">تغيير حالة
                    الطلب</label>
                <select class="form-select" name="order_status_id" id="validationCustom04" required>
                    @forelse($orderStatus as $status)
                        <option value="{{ $status->id }}" @selected($order->order_status_id == $status->id)>{{ $status->CurrentName }}
                        </option>
                    @empty
                    @endforelse
                </select>
                <div class="invalid-feedback">
                    Please select a valid state.
                </div>
            </div>
            <button class="btn btn-primary mt-5" type="submit">حفظ الحالة
            </button>
        </td>

    </form>



    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>

        var firebaseConfig = {
            apiKey: "AIzaSyC3tzyv__3udwxPWI5swk12qoGZ4J5sb1c",
            authDomain: "test-notification-3f882.firebaseapp.com",
            projectId: "test-notification-3f882",
            storageBucket: "test-notification-3f882.firebasestorage.app",
            messagingSenderId: "786873585382",
            appId: "1:786873585382:web:fe4db5ece2173b8eaaf527",
            measurementId: "G-31WKBC1QJW"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function (token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });

                }).catch(function (err) {
                console.log('User Chat Token Error' + err);
            });
        }

        messaging.onMessage(function (payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });

    </script>
@endsection
@push('scripts')
    <!-- Bootstrap rating js -->
    <script src="{{ asset('assets/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/rating-init.js') }}"></script>

    {{--    <script--}}
    {{--            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuK5dAqp6_Fs7d58Qs-5SvJeyHECUJAoM&callback=initMap"--}}
    {{--            async--}}
    {{--            defer>--}}
    {{--    </script>--}}

@endpush