@extends('front.profile.index')

@section('page_title', 'تغيير كلمة السر')
<link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">


@section('user_section')
    <div class="myAccount-page">
        <div id="acc-5" class="tab-pane fade in">
            <h3>{{__('profile.CHANGE_PASSWORD')}}</h3>
            <h5>{{__('profile.MANAGE_DETAILS_AND_STATUS')}}</h5>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info">
                    <h5>{{__('profile.ENTER_NEW_PASSWORD')}}</h5>
                    <hr>
                    <form action="{{ route('update_password') }}" method="post">
                        @csrf
                        @method('put')

                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <label>{{__('profile.PASSWORD')}}</label>
                            <input type="password" name="new_password">
                            @error('new_password')
                                <span class="error" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <label>{{__('profile.CONFIRM_PASSWORD')}}</label>
                            <input type="password" name="new_password_confirmation">
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <button type="submit" style="border: none" class="changePassword">
                                <a>{{__('profile.SAVE_CHANGES')}}</a>
                            </button>
                        </div>
                    </form>


                </div>
            </div>

        </div>
    </div><!--acc-3-->
@endsection
