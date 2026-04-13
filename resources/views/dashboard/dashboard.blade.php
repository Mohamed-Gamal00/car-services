@extends('dashboard.index')

@section('breadcrumb')
    @parent
@endsection

@section('section')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 style="color: #252525" class="page-title">لوحة التحكم - Quick Clean</h5>
            </div>
        </div>
    </div>
    
    <!-- Main Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/products.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد الخدمات</h5>
                        <h4 class="fw-medium font-size-24">{{$servicesCount}}</h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('services.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-success text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/products.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد الباقات</h5>
                        <h4 class="fw-medium font-size-24">{{$packagesCount}}</h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('packages.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-info text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/orders.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد الطلبات</h5>
                        <h4 class="fw-medium font-size-24">{{$ordersCount}}</h4>
                        <p class="text-white-50 mb-0">اليوم: {{$todayOrders}}</p>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('orders.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-warning text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/users.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد العملاء</h5>
                        <h4 class="fw-medium font-size-24">{{$usersCount}}</h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('clients.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Captain & Revenue Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/admins.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد الكباتن</h5>
                        <h4 class="fw-medium font-size-24">{{$captainsCount}}</h4>
                        <p class="text-white-50 mb-0">متاح: {{$availableCaptains}} | مشغول: {{$busyCaptains}}</p>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('captains.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-success text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <i class="mdi mdi-cash-multiple h2"></i>
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">إجمالي الإيرادات</h5>
                        <h4 class="fw-medium font-size-24">{{number_format($totalRevenue, 2)}} ر.س</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-info text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/admins.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد المدراء</h5>
                        <h4 class="fw-medium font-size-24">{{$adminsCount}}</h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('admins.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-warning text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-start mini-stat-img me-4">
                            <img src="{{asset('assets/images/services-icon/email.png')}}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase text-white-50">عدد الرسائل</h5>
                        <h4 class="fw-medium font-size-24">{{$messagesCount}}</h4>
                    </div>
                    <div class="pt-2">
                        <div class="float-end">
                            <a href="{{route('contact_us.index')}}" class="text-white-50">
                                <i class="mdi mdi-arrow-right h5 text-white-50"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection