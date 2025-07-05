@extends('dashboard.index')

@section('title', 'تعديل باقة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">الباقات</a></li>
    <li class="breadcrumb-item">تعديل باقة</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-12">
            <div class="card">
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
                    <form class="repeater" action="{{ route('packages.update', $package->id) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div>
                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                                    الباقة</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="name" type="text" id="example-text-input"
                                           value="{{ $package->name }}">
                                    @error('name')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم الباقة
                                    باللغة
                                    الانجليزية</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" name="name_en" value="{{ $package->name_en }}"/>
                                    @error('name_en')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">السعر
                                    الحالي</label>
                                <div class="col-sm-10">
                                    <x-form.input type="number" name="price" value="{{ $package->price}}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">وقت
                                    الخدمة</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" placeholder="HH:mm" name="duration"
                                                  value="{{ $package->duration  }}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">عدد مرات
                                    الغسيل</label>
                                <div class="col-sm-10">
                                    <x-form.input type="number" name="wash_count" value="{{ $package->wash_count}}"/>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">
                                    صلاحية الباقة
                                    بالايام</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" placeholder="" name="validity_in_days"
                                                  value="{{ $package->validity_in_days }}"/>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الوصف</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="7"
                                              name="description">{{ $package->description }}</textarea>
                                </div>
                            </div>


                            {{-- حالة النشاط --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label fw-bold">حالة الباقة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="is_active" aria-label="Default select example">
                                        @error('status')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                        <option value="" hidden disabled>اختر حالة الباقة</option>
                                        <option value="1" @selected($package->is_active == '1')>نشط</option>
                                        <option value="0" @selected($package->is_active == '0')>غير نشط
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <x-dashboard.image-preview image="{{ asset('storage/' . $package->icon ) }}" fileName="icon"
                                                       width="150"
                                                       heigh="150" title="ايقونة الباقة"/>

                            <x-dashboard.image-preview image="{{ asset('storage/' . $package->image) }}"
                                                       fileName="image" width="150"
                                                       heigh="150" title="الصورة الرئيسية"/>

                            <x-dashboard.image-preview image="{{ asset('storage/' . $package->image_en) }}"
                                                       fileName="image_en" width="150"
                                                       heigh="150" title="الصورة الرئيسية لغة انجليزية"/>


                            {{-- مميزات --}}

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label fw-bold">مميزات للباقة (اختياري)</label>
                                <div class="col-sm-10">
                                    <div data-repeater-list="package_features">

                                        @if (empty($package->features->first()->feature))
                                            <div class="row" data-repeater-item>
                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="name">الاسم</label>
                                                    <input type="text" id="name" name="feature"
                                                           class="form-control" placeholder="اكتب اسم الميزه"/>
                                                    @error('feature')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="feature_en">الاسم
                                                        بالانجليزي</label>
                                                    <input type="text" id="feature_en"
                                                           name="feature_en"
                                                           class="form-control" placeholder="الاسم بالانجليزي"/>
                                                    @error('feature_en')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- end col -->
                                                <input name="feature_id" hidden>

                                                <div class="col-lg-2 col-sm-4 align-self-center">
                                                    <label class="form-label fw-bold"></label>
                                                    <div class="d-grid">
                                                        <input data-repeater-delete type="button"
                                                               class="btn btn-primary mb-2" value="مسح"/>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif


                                        @foreach ($package->features as $feature)
                                            <div class="row" data-repeater-item>
                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="name">الاسم</label>
                                                    <input type="text" id="name" name="feature"
                                                           value="{{ $feature->feature }}"
                                                           class="form-control"
                                                           placeholder="اكتب اسم الميزه"/>
                                                    @error('feature')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-lg-2">
                                                    <label class="form-label fw-bold" for="feature_en">الاسم
                                                        بالانجليزي</label>
                                                    <input type="text" id="feature_en"
                                                           name="feature_en"
                                                           value="{{ $feature->feature_en }}"
                                                           class="form-control" placeholder="الاسم بالانجليزي"/>
                                                    @error('feature_en')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <input value="{{ $feature->id }}" name="feature_id" hidden>

                                                <input value="{{ $feature->id }}" name="feature_delete" hidden>


                                                <div class="col-lg-2 col-sm-4 align-self-center">
                                                    <label class="form-label fw-bold"></label>
                                                    <div class="d-grid">
                                                        <input data-repeater-delete type="button"
                                                               class="btn btn-primary mb-2" value="مسح"/>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                    <input data-repeater-create type="button"
                                           class="btn btn-success mt-2 mt-sm-0" value="اضافة المزيد"/>
                                </div>
                            </div>


                            <div>
                                <button id="submitBtn" class="btn btn-primary mb-5" type="submit">حفظ الباقة</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.colors-select').select2();

    });
</script>


<script>
    document.getElementById('category').addEventListener('change', function () {
        var categoryId = this.value;
        var subcategorySelect = document.getElementById('subcategory');

        // Clear existing options
        subcategorySelect.innerHTML = '';

        // If no main category is selected, hide subcategory select


        subcategorySelect.style.display = 'block'; // Show subcategory select

        fetch(`/admin/sub_category/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                var defaultOption = new Option('', '');
                subcategorySelect.appendChild(defaultOption);

                data.forEach(subcategory => {
                    var option = new Option(subcategory.name, subcategory.id);
                    subcategorySelect.appendChild(option);
                });
            });
    });
</script>

{{-- edit images scripts and styley --}}

<style>
    .image-upload-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .image-upload-one {
        margin: 10px;
    }

    .image-container {
        position: relative;
        width: 200px;
        height: 200px;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-container {
        position: relative;
        display: inline-block;
    }

    .image-container:hover .overlay {
        opacity: 1;
    }

    .image-container:hover .button-remove2 {
        opacity: 1;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Adjust opacity to your preference */
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .button-remove2 {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(1);
        /* Adjust the transform origin to center */
        background-color: transparent;
        border: none;
        color: #fff;
        /* Adjust color as per your design */
        font-weight: bold;
        font-size: 40px;
        padding: 8px 12px;
        border-radius: 50%;
        cursor: pointer;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        /* Apply transition for both opacity and transform */
        z-index: 1;
        opacity: 0;
    }

    .button-remove2:hover {
        transform: translate(-50%, -50%) scale(1.2);
        /* Scale up the button slightly more on hover */
    }

    .image-upload-one {
        animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<script>
    window.onload = function () {
        addProductImageUploadEdit();
    };

    function previewImage(event, index) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById(`preview-${index}`);
            preview.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }

    function removeImage(index) {
        const preview = document.getElementById(`preview-${index}`);
        const input = document.getElementById(`file-ip-${index}`);
        input.value = "";
        const container = document.querySelector(
            `.image-upload-container #image-upload-${index}`
        );
        container.parentNode.removeChild(container);
    }

    let uploadIndex = 2; // Start with 2 as there's already one input

    function addProductImageUploadEdit() {
        const container = document.querySelector(".image-upload-container");
        const newInput = document.createElement("div");
        newInput.classList.add("image-upload-one");
        newInput.innerHTML = `
                <div class="image-upload-container"> 
                  <div class="image-upload-one" id="image-upload-${uploadIndex}">
                    <div class="center">
                      <div class="form-input">
                        <label for="file-ip-${uploadIndex}">
                          <div class="image-container">
                            <img alt="Preview" src="" data-holder-rendered="true" id="preview-${uploadIndex}">
                            <div class="overlay"></div>
                            <input type="file" hidden id="file-ip-${uploadIndex}" name="header[${uploadIndex}][image]" accept="image/*" onchange="previewImage(event, ${uploadIndex})">
                            <button class="button-remove2" type="button" onclick="removeImage(${uploadIndex})">x</button>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              `;
        container.appendChild(newInput);

        const baseUrl = window.location.origin;
        const relativeImagePath = "/assets/images/upload-image.jpg";
        const fullImageUrl = baseUrl + relativeImagePath;
        document.getElementById(`preview-${uploadIndex}`).src = fullImageUrl;

        newInput.style.opacity = 0;
        setTimeout(() => {
            newInput.style.opacity = 1;
        }, 10);
        uploadIndex++;
    }
</script>


<script>
    function confirmProductImageDelete(imageId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن يمكنك التراجع عن هذا الإجراء!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، قم بالحذف!',
            cancelButtonText: 'لا، ألغِ الأمر'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteProductImage(imageId);
            }
        });
    }

    function deleteProductImage(imageId) {
        console.log(imageId);
        fetch('{{ route('image.delete') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                image_id: imageId
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log the response for debugging
                if (data.message === 'Image Deleted Successfully') {
                    document.getElementById(`image-${imageId}`).remove();
                    Swal.fire(
                        'تم الحذف!',
                        'تم حذف الصورة بنجاح.',
                        'success'
                    );
                } else {
                    Swal.fire(
                        'فشل!',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'فشل!',
                    'فشل في حذف الصورة.',
                    'error'
                );
            });
    }
</script>
@push('scripts')
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
@endpush
