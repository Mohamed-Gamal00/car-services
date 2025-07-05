<div>
    <form action="" wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="row mb-3">
            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">اسم
                المنتج</label>
            <div class="col-sm-10">
                <input class="form-control" name="name" type="text" id="example-text-input" wire:model="name">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">السعر</label>
            <div class="col-sm-10">
                <input class="form-control" name="price" type="number" wire:model="price">
                @error('price')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الخصم</label>
            <div class="col-sm-10">
                <input class="form-control" type="number" name="discount_price" wire:model="discount_price">
                @error('discount_price')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الكميه</label>
            <div class="col-sm-10">
                <input class="form-control" type="number" name="quantity" wire:model="quantity">
                @error('quantity')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <input class="form-control" type="hidden" name="quantity" wire:model="main_category_setting_id">


        <div class="row mb-3">
            <label for="example-number-input" class="col-sm-2 col-form-label fw-bold">الوصف</label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="7" name="description" wire:model="description"></textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if ($companies)
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label fw-bold">البراند</label>
                <div class="col-sm-10">
                    <select name="company_id" class="form-select" aria-label="البراند الخاص بالمنتج"
                        wire:model="company_id">
                        <option value="" hidden>اختر البراند الخاص بالمنتج</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">
                                {{ $company->CurrentNameLang }}</option>
                        @endforeach

                    </select>
                    @error('company_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endif



        @if ($categories)
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label fw-bold">القسم</label>
                <div class="col-sm-10">
                    <select name="category_id" class="form-select" aria-label="اختر القسم الرئيسي للمنتج"
                        wire:model.live="selectedCategory" wire:model.defer="category_id">
                        <option value="" hidden>اختر القسم الرئيسي الخاص بالمنتج</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id ?? '' }}">
                                {{ $category->CurrentNameLang }}</option>
                        @endforeach

                    </select>
                    @error('category_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endif

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label fw-bold">القسم الفرعي</label>
            <div class="col-sm-10">
                <select wire:model.live="selectedFirstSubCategory" name="company_id" class="form-select"
                    aria-label="اختر القسم الفرعي للمنتج">
                    <option value="" hidden>اختر القسم الفرعي الخاص بالمنتج</option>
                    @if ($firstSubCategory)
                        @foreach ($firstSubCategory->settings as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->CurrentNameLang }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label fw-bold">حالة المنتج</label>
            <div class="col-sm-10">
                <select class="form-select" name="status" aria-label="Default select example" wire:model="status">
                    @error('status')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <option selected>Open this select menu</option>
                    <option value="active">نشط</option>
                    <option value="archived">غير نشط</option>
                </select>
                @error('status')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-5">
            <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الصورة الرئيسية</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" name="image" data-buttonname="btn-secondary"
                    wire:model="image">
                @error('image')
                    <span class="error">{{ $message }}</span>
                @enderror

                <!-- Display uploaded main product image -->
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail rounded me-2 col-sm-2 mt-2"
                        alt="200x200" width="200">
                @endif
            </div>
        </div>

        <!-- Button to add more images -->

        @if ($showMoreInputs)
            @foreach ($images as $key => $image)
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label fw-bold">الصورة {{ $key + 1 }}</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" name="images[]"
                            wire:model="images.{{ $key }}">
                    </div>
                </div>
            @endforeach
        @endif

        <div class="row mb-3">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <button type="button" wire:click="addMoreImages" class="btn btn-success">إضافة صورة أخرى</button>
                @if ($errors->has('images.' . (count($images) - 1)))
                    <span class="error">{{ $errors->first('images.' . (count($images) - 1)) }}</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                @foreach ($images as $key => $image)
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" alt="Uploaded Image"
                            height="60px" width="60px" style="display: inline-block; margin-right: 10px;">
                    @endif
                @endforeach
            </div>
        </div>
        <div class="col-sm-6 text-end" wire:loading>
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>


        <div class="row">
            <label class="fw-bold mb-3" style="font-size: 17px;">الألوان</label>
            @forelse ($colors as $color)
                <div class="mb-3 col-lg-2">
                    <div class="form-check">

                    </div>

                </div>
            @empty
                <div>لا يوجد تصنيفات</div>
            @endforelse

        </div>


        <button class="btn btn-primary" type="submit" wire:loading.remove>حفظ المنتج</button>
    </form>
</div>
