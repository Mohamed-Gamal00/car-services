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
                    @livewire('admin.auth.admin-login-component')

                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.scripts')

</body>

</html>