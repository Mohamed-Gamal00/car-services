@extends('dashboard.index')

@section('title', 'اضافة سيارة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">السيارات</a></li>
    <li class="breadcrumb-item">اضافة سيارة</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    <form action="{{ route('cars.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="col-sm-10">


                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم السيارة
                                    بالعربي</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" name="name_ar" value="{{ old('name_ar') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم السيارة
                                    بالانجليزي</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" name="name_en" value="{{ old('name_en') }}"/>
                                </div>
                            </div>
                        </div>


                        <div>
                            <button class="btn btn-primary" type="submit">حفظ</button>
                        </div>
                    </form>


                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div> <!-- end col -->
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('country').addEventListener('change', function () {
            var selectedCountryId = this.value;
            var cityCheckboxes = document.querySelectorAll('.city-checkbox');

            cityCheckboxes.forEach(function (checkbox) {
                var countryId = checkbox.getAttribute('data-country-id');
                if (selectedCountryId === '' || countryId !== selectedCountryId) {
                    checkbox.parentElement.parentElement.style.display = 'none';
                } else {
                    checkbox.parentElement.parentElement.style.display = 'block';
                }
            });
        });
    </script> --}}

@endsection
