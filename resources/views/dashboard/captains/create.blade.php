@extends('dashboard.index')
@section('title', 'اضافة كابتن جديد')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item">اضافة كابتن جديد</li>
@endsection


@section('section')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    <form autocomplete="off" method="post" action="{{ route('captains.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الاسم</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="name" type="text" id="example-text-input"
                                       value="">
                                @error('name')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الاسم الاخير</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="last_name" type="text" id="example-text-input"
                                       value="">
                                @error('last_name')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">رقم الجوال
                            </label>
                            <div class="col-sm-10">
                                <input class="form-control" name="phone" type="phone"
                                       id="example-text-input"
                                       value="">
                                @error('phone')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الرقم السري</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="password" type="password"
                                       id="example-text-input"
                                       value="">
                                @error('password')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label">تأكيد الرقم
                                السري</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="password_confirmation" type="password"
                                       id="example-text-input"
                                       value="">
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary mt-5" type="submit">حفظ</button>
                        </div>
                    </form>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div> <!-- end col -->

        {{-- sub category in edit page --}}
    </div>

@endsection
