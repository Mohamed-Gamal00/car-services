@extends('dashboard.index')
@section('title', 'تعديل البيانات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item">تعديل بايانات كابتن</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert type="success"/>
                <x-alert type="dark"/>
                <x-alert type="danger"/>
                <div class="card-body">
                    {{-- Form Start --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('captains.update', $captain->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')


                        <div class="row mb-3 mt-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الاسم
                                الاول</label>
                            <div class="col-sm-10">
                                <x-form.input type="text" value="{{$captain->name}}" name="name"/>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">رقم
                                الجوال</label>
                            <div class="col-sm-10">
                                <x-form.input type="number" value="{{$captain->phone}}" name="phone"/>
                            </div>
                        </div>


                        <div class="row mb-3 mt-3">
                            <label for="is_active" class="col-sm-2 col-form-label fw-bold">النشاط</label>
                            <div class="col-sm-10">
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ $captain->is_active == 1 ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ $captain->is_active == 0 ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <label for="status" class="col-sm-2 col-form-label fw-bold">الحالة</label>
                            <div class="col-sm-10">
                                <select name="status" id="is_active" class="form-control">
                                    <option value="available" {{ $captain->status == 'available' ? 'selected' : '' }}>
                                        متاح
                                    </option>
                                    <option value="busy" {{ $captain->status == 'busy' ? 'selected' : '' }}>مشغول
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-5" type="submit">حفظ</button>

                    </form>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div> <!-- end col -->

        {{-- sub category in edit page --}}
    </div>

    <div class="row">
        <div class="col-12">
            <div class="font-size-18 fw-bold mb-2">تغيير الرقم السري</div>
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    <form method="post" action="{{ route('captain.update_password', $captain->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')


                        <div class="row mb-3">
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الرقم
                                السري</label>
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
                            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">تأكيد الرقم
                                السري</label>
                            <div class="col-sm-10">
                                <input class="form-control" name="password_confirmation" type="password"
                                       id="example-text-input"
                                       value="">
                            </div>
                        </div>
                        <button class="btn btn-primary mt-5" type="submit">حفظ</button>

                    </form>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div> <!-- end col -->

        {{-- sub category in edit page --}}
    </div>




    {{-- Form End --}}

@endsection
