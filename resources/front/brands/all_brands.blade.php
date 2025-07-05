@extends('front.index')

@push('styles')
  <link rel="stylesheet" href="{{ asset('front/css/old-style.css') }}">

@endpush
@section('front-section')
    <div class="shop-category">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head-bar">
                        <h3>{{__('general.BRANDS')}}</h3>
                    </div>
                </div>
                @forelse($brands as $brand)
                    <div class="col-md-2 col-sm-3 col-xs-6">
                        <div class="one">
                            <div class="pic">
                                <a href="{{route('front.products')}}">
                                    <img src="{{$brand->image_url}}" alt="">
                                </a>
                            </div>
                            <a href="{{route('front.products')}}">{{$brand->name}}</a>
                        </div>

                    </div>
                @empty
                @endforelse

            </div>
        </div>
    </div>
@endsection
