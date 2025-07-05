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
                            <li>{{__('index.MAIN')}}</li>
                            <li> | </li>
                            <li>{{__('orders.WHOLESALE_ORDER_FORM')}}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="content">
                        <h4>{{__('orders.WHOLESALE_ORDER_FORM')}}</h4>
                        <p>{{__('orders.WHOLESALE_ORDER_INFO')}}
                        </p>
                        <form action="{{ route('bulk_order.storeorder') }}" method="post">
                            @csrf

                            <label> {{__('orders.FULL_NAME')}}</label>
                            <input name="name" type="text">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <label> {{__('orders.PHONE_NUMBER')}}</label>
                            <input name="phone" type="text">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <label> {{__('orders.COMPANY_OR_ENTITY_NAME')}}</label>
                            <input name="company_name" type="text">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <label>{{__('orders.REQUESTED_PRODUCT_TYPES')}}</label>
                            <textarea name="description"></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <button type="submit">{{__('orders.SEND_FORM')}}</button>
                        </form>
                      
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
