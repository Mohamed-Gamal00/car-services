@extends('front.index')

@section('page_title', 'تغيير كلمة السر')


@section('front-section')

    <div class="road">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <ul>
                        <li><a href="{{route('front.home')}}">الرئيسية</a></li>
                        <li> /</li>
                        <li>تغيير كلمة السر</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="login">

                        <h4>اعادة تعيين كلمة السر</h4>
                        <form method="POST" action="{{ route('password.update', $request->route('token')) }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <label>البريد اللكتروني</label>
                            <input readonly type="email" name="email" placeholder="ادخل البريد الالكتروني"
                                   value="{{old('email', $request->email)}}">
                            @error('email')
                            <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <label>كلمة المرور </label>
                            <input name="password" type="password" placeholder="ادخل كلمة المرور">
                            @error('password')
                            <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <label>تأكيد كلمة المرور </label>
                            <input name="password_confirmation" type="password"
                                   placeholder="ادخل كلمة المرور">
                            @error('password_confirmation')
                            <span class="error" style="color: red">{{ $message }}</span>
                            @enderror

                            <button type="submit">حفظ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
