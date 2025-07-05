@extends('dashboard.index')

@section('title', 'الاعدادات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الاعدادات</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-alert type='success'/>
                    <form class="form form-vertical" action="{{route('notification.Dashboard.store')}}"
                          method="post">
                        @csrf

                        <div class="form-body">


                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical">العنوان</label>
                                        <input type="text" id="first-name-vertical" class="form-control"
                                               name="title" placeholder="العنوان">
                                        @error('title')
                                        <span class=" text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row  mb-3" style="padding-bottom: 30px">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-vertical"> المحتوي </label>
                                        <textarea name="description" id="first-name-vertical"
                                                  class="form-control"></textarea>
                                        @error('description')
                                        <span class=" text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>


                            <div class="col-12" style="text-align: center">
                                <button type="submit"
                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">اضافة
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection