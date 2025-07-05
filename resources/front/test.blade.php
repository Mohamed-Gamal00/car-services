@extends('front.profile.index')

@section('page_title', 'الارجاع')


@section('user_section')

    <div class="account-content">
        <div id="acc-2" class="tab-pane fade in">
            <h3>الارجاع</h3>
            <h5>قائمة بكل طلبات الارجاع </h5>


            @forelse($products as $order)
                @foreach($order->products as $item)

                    <div class="col-md-8 col-sm-12 col-xs-12">


                        <div class="product">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="statues">
                                    <ul>
                                        @forelse($orderStatus as $status)
                                            <li class="{{$order->order_status_id == $status->id ? 'active' : ''}}"><i
                                                    class="fa fa-check-circle"></i>{{$status->name}}</li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <div class="pic">
                                        <img src="{{$item->image_url}}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="content">
                                        <h4>{{$item->name}}</h4>
                                        <span>{{(resolve('App\currency\Currency')->getCurrency($item->price))}}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @empty
                <div id="acc-3" class="tab-pane fade in">
                    <div class="col-md-12 col-md-12 col-xs-12">
                        <div class="restore">
                            <img src="{{asset('front/images/restore.svg')}}" alt="">
                            <h4>لا توجد طلبات إرجاع</h4>
                            <p>لم تقم بتقديم أي طلب إرجاع لمنتجاتك السابقة</p>
                            {{-- <a href="{{route('user.orders')}}">انشاء ارجاع جديد</a> --}}
                        </div>
                    </div>
                </div><!--acc-3-->
            @endforelse

        </div>


    </div><!--Two-->
    <div class="col-md-8 col-sm-12 col-xs-12">
        {{$products->links('pagination.custom_pagination')}}
        <style>
            .pagination {
                float: left;
                display: inline;
            }
        </style>
    </div>
@endsection