@extends('front.profile.index')

@section('page_title', 'البيانات الشخصيه')

@section('breadcrumb')
    <li> حسابك </li>
@endsection

@section('user_section')
    <div class="account">
        <div id="acc-5" class="tab-pane fade in">
            <x-front.alert type="info" />

            <div id="EditInfo" class="tab-pane fade in">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="addressContent">
                        <h3>{{__('profile.EDIT_ACCOUNT_DETAILS')}}</h3>
                        <p> {{__('profile.MANAGE_DETAILS')}}</p>
                        <form action="{{ route('update_user_info') }}" method="post">
                            @csrf
                            @method('put')
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <label> {{__('profile.FIRST_NAME')}}</label>
                                <input type="text" name="first_name" value="{{ $user->first_name }}">
                                @error('first_name')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <label> {{__('profile.LAST_NAME')}}</label>
                                <input type="text" name="family_name" value="{{ $user->family_name }}">
                                @error('family_name')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <label> {{__('profile.EMAIL')}}</label>
                                <input type="text" name="email" value="{{ $user->email }}">
                                @error('email')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4 col-sm-4 col-xs-6">
                                <label> كلمه السر</label>
                                <input type="text" placeholder="  كلمه السر">
                            </div> --}}
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <label> {{__('profile.PHONE')}}</label>
                                <input type="number" name="phone_number" value="{{ $user->phone_number }}">
                                @error('phone_number')
                                    <span class="error" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="links">
                                    <a href="{{route('user_password')}}">{{__('profile.CHANGE_PASSWORD')}}</a>
                                    <button
                                        style=" background-color: #2C368E;    width: 200px;
                                    padding: 12px;
                                    color: #fff;
                                    text-align: center;
                                    font-size: 14px;
                                    border: none;
                                    display: inline-block;
                                    border-radius: 8px;
                                    margin-left: 12px;
                                    margin-top: 22px;">

                                        {{__('profile.SAVE_CHANGES')}}</button>
                                </div>

                            </div>
                        </form>


                    </div>
                </div>

            </div>

        </div>
    </div><!--acc-3-->

@endsection
