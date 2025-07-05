@extends('front.index')

@section('page_title', 'تواصل معنا')

@section('front-section')
    <div class="bulk-orders">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li><img src="images/home.png" alt=""></li>
                            <li>الرئيسية</li>
                            <li> | </li>
                            <li> {{__('index.PRIVACY_POLICY')}}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="privacyPolicy">
                      {!! translateWithHTMLTags($page->content) !!}
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
