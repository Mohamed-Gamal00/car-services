@extends('front.index')

@section('page_title', 'من نحن')

@section('front-section')
<div class="bulk-orders">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="road">
          <ul>
            <li><img src="images/home.png" alt=""></li>
            <li>{{__('index.ABOUT_US')}}</li>
            <li> | </li>
            <li> {{__('index.ABOUT_US')}}</li>
          </ul>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="aboutUs">
          {!! translateWithHTMLTags($about_us->content) !!}
        </div>
      </div>


    </div>
  </div>
</div>
@endsection
