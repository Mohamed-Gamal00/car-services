@extends('front.index')

@section('page_title', 'تواصل معنا')

@section('front-section')
    <x-front.alert type="success" />
    <div class="bulk-orders">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>الرئيسية</li>
                            <li> | </li>
                            <li> {{__('contact_us.CONTACT_US')}} </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="content">
                        <h4>{{__('contact_us.CONTACT_US')}}</h4>
                        <p>{{__('contact_us.HAVE_QUESTION_OR_SUGGESTION')}}</p>
                        <form action="{{ route('contact_us.store') }}" method="post">
                            @csrf

                            <label>{{__('contact_us.FULL_NAME')}}</label>
                            <input name="full_name" type="text">
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <label>{{__('contact_us.EMAIL')}}</label>
                            <input name="contact_email" type="text">
                            @error('contact_email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <label>{{__('contact_us.MOBILE_NUMBER')}}</label>
                            <input name="phone_number" type="text">
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <label>{{__('contact_us.MESSAGE')}} </label>
                            <textarea name="text"></textarea>
                            @error('text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button>{{__('contact_us.SEND')}}</button>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
