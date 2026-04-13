<!doctype html>
<html lang="en" dir="rtl">

@include('layouts.header')

<body>

<div class="home-btn d-none d-sm-block">
    {{--    <a href="{{route('front.home')}}" class="text-dark"><i class="fas fa-home h2"></i></a>--}}
</div>

<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card overflow-hidden">
                    <div class="bg-primary">
                        <div class="text-primary text-center p-4">
                            <h5 class="text-white font-size-20">مرحبا بعودتك !</h5>
                            <a href="" class="logo logo-admin">
                                <img src="{{ asset('assets/images/logo-sm.png') }} " height="24" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3">
                            <form class="mt-4" method="POST" action="{{ route('admin.login.submit') }}">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label" for="email">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="أدخل البريد الإلكتروني" value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password">كلمة المرور</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="أدخل كلمة المرور" required>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">تذكرني</label>
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-12 text-end">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                            تسجيل الدخول
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.scripts')

</body>

</html>