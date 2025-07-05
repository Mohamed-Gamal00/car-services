@extends('front.index')


@section('front-section')

    <style>
        .intro {
            background-image: url("{{asset('storage/'. $mainDesign->image) }}");
            background-position: center center;
            background-size: cover;
            padding: 180px 0px;
            background-repeat: no-repeat;
        }
    </style>

    <div class="intro">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="main-slider">
                        @forelse($headerText as $text)
                            <div class="item">
                                <div class="content">
                                    <h4>{{$text->title}}</h4>
                                    <p>{{Str::limit($text->description, 400)}}</p>
                                    <a href="">اعرف أكثر</a>
                                </div>
                            </div>
                        @empty
                            <div></div>
                        @endforelse

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="shop-category">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="head-bar">
                        <h3>تسوق حسب الفئات <a href="">عرض الكل</a></h3>
                    </div>
                </div>
                @forelse($categories as $category)
                    <div class="col-md-2 col-sm-3 col-xs-6">
                        <div class="one">
                            <div class="pic">
                                <img src="{{$category->image_url}}" alt="">
                            </div>
                            <a href="">{{$category->name}}</a>
                        </div>

                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <!--=====================================================================-->
    <div class="all-products-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="road">
                        <ul>
                            <li> الرئيسية</li>
                            <li> /</li>
                            <li> كل الخدمات</li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="filter">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="accordion"> الفئة</button>
                            <div class="panel">
                                <label class="bg">الكل
                                    <input type="radio" checked="checked" name="category">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">(الإلكترونيات والموبايلات)
                                    <input type="radio" name="category">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>
                                <label class="bg">(سماعات أذن)
                                    <input type="radio" name="category">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>
                                <label class="bg">(مكبرات صوت)
                                    <input type="radio" name="category">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">(سماعات أذن)
                                    <input type="radio" name="category">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="accordion"> الماركة</button>
                            <div class="panel">
                                <label class="bg">الكل
                                    <input type="radio" checked="checked" name="brand">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">(اتش بي)
                                    <input type="radio" name="brand">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>
                                <label class="bg">(أبل)
                                    <input type="radio" name="brand">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>
                                <label class="bg">(سامسونج)
                                    <input type="radio" name="brand">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">(لينوفو)
                                    <input type="radio" name="brand">
                                    <span class="checkmark"></span>
                                    <span class="num"> (120)</span>
                                </label>

                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="accordion"> السعر</button>
                            <div class="panel">
                                <input type="number" class="price" placeholder="من">
                                <input type="number" class="price" placeholder="الي">


                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="accordion"> اللون</button>
                            <div class="panel">
                                <label class="bg">الكل
                                    <input type="radio" checked="checked" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #d80707"></span>
                                    الاحمر
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (8)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #0000ff"></span>
                                    الأزرق
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (12)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #ffff00"></span>
                                    الأصفر
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (22)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #d80707"></span>
                                    الاحمر
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (8)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #0000ff"></span>
                                    الأزرق
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (12)</span>
                                </label>
                                <label class="bg">
                                    <span class="color-c" style="background-color: #ffff00"></span>
                                    الأصفر
                                    <input type="radio" name="color">
                                    <span class="checkmark"></span>
                                    <span class="num"> (22)</span>
                                </label>

                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="accordion"> التقييم</button>
                            <div class="panel">
                                <label class="bg">
                                    <img src="images/rate-5.svg" alt="">
                                    <input type="radio" checked="checked" name="rate">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">
                                    <img src="images/rate-4.svg" alt="">
                                    <input type="radio" checked="checked" name="rate">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">
                                    <img src="images/rate-3.svg" alt="">
                                    <input type="radio" checked="checked" name="rate">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>
                                <label class="bg">
                                    <img src="images/rate-2.svg" alt="">
                                    <input type="radio" checked="checked" name="rate">
                                    <span class="checkmark"></span>
                                    <span class="num"> (100)</span>
                                </label>


                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="reset">
                                <button>اعادة ظبط</button>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="sort">
                            <form action="">
                                <label>ترتيب حسب : </label>
                                <select name="">
                                    <option value=""> من الاعلى الى الاقل</option>
                                    <option value=""> من الاعلى الى الاقل</option>
                                    <option value=""> من الاعلى الى الاقل</option>

                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-2.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-1.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="recently"> وصل حديثآ</div>
                            <img class="pic" src="images/pro-3.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-1.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-2.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="new"> جديد</div>
                            <img class="pic" src="images/pro-1.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-1.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-2.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="pro">
                            <div class="discount">خصم 20%</div>
                            <img class="pic" src="images/pro-1.svg" alt="">
                            <h3>موبايلات</h3>
                            <a href="">موبايل سامسونج جي 12 4 جيجا رام</a>
                            <h5>235.00 ر.س <span> 275.00 ر.س</span></h5>
                            <div class="more">
                                <button class="addToCart"><img src="images/cart-w-icon.svg"> اضف للسلة</button>
                                <button class="like"><img src="images/heart-icon.svg"></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
