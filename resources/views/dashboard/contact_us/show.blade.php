@extends('dashboard.index')

@section('title', 'مشاهدة الرسالة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('contact_us.index')}}">الرسائل</a></li>
    <li class="breadcrumb-item active">مشاهدة الرساله</li>
@endsection

@section('section')

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الاسم</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" value="{{$message->name}}"
                                       placeholder="Artisanal kale"
                                       id="example-text-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-search-input" class="col-sm-2 col-form-label">البريد الالكتروني</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" value="{{$message->email}}"
                                       placeholder="How do I shoot web"
                                       id="example-search-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-email-input" class="col-sm-2 col-form-label">الجوال</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" value="{{$message->phone_number}}"
                                       placeholder="bootstrap@example.com"
                                       id="example-email-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="example-url-input" class="col-sm-2 col-form-label">الرسالة</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" style="direction: rtl;height: 200px;" type="url"
                                >{{$message->message}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--        <div class="d-flex mb-4">--}}
        {{--            <div class="flex-shrink-0 me-3">--}}
        {{--            </div>--}}
        {{--            <div class="flex-grow-1">--}}
        {{--                <small class="text-muted">الاسم :</small>--}}
        {{--                <h4 class="font-size-15 m-0">{{$message->name}}</h4>--}}
        {{--            </div>--}}

        {{--            <div class="flex-grow-1">--}}
        {{--                <small class="text-muted">البريد الالكتروني :</small>--}}
        {{--                <h4 class="font-size-15 m-0">{{$message->email}}</h4>--}}
        {{--            </div>--}}

        {{--            <div class="flex-grow-1">--}}
        {{--                <small class="text-muted">رقم الجوال :</small>--}}
        {{--                <h4 class="font-size-15 m-0">{{$message->phone_number}}</h4>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        {{--        <h4 class="font-size-16">الرسالة</h4>--}}

        {{--        <p>{{$message->message}}</p>--}}
    </div>

@endsection
