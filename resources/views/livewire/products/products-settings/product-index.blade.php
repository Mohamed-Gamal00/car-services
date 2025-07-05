<div>

    <div class="col mt-4">
        <input wire:model.live="search" class="form-control" type="text" placeholder="البحث عن منتج...">
    </div>

    <div class="table-responsive mt-2">

        <table class="table table-edits table-striped table-bordered mt-5">
            <thead>
                <tr>
                    <th></th>
                    <th class="fw-bold"></th>
                    <th class="fw-bold">اسم المنتج</th>
                    <th class="fw-bold">حالة المنتج</th>
                    <th class="fw-bold">القسم الرئيسي</th>
                    <th class="fw-bold">القسم الفرعي</th>
                    <th class="fw-bold">الفلاتر</th>
                    <th></th>
                    <th></th>


                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr data-id="5">
                        <td>
                            <input class="form-check-input m-1" type="checkbox" value="{{ $product->id }}"
                                name="products[]" style="height: 19px;">
                        </td>
                        <td colspan="1" data-field="id">
                            <img class="img-thumbnail rounded me-2" width="40" height="40"
                                src="{{ $product->image_url }}" data-holder-rendered="true">
                        </td>
                        <td data-field="id">{{ $product->CurrentNameLang }}</td>
                        <td data-field="id">{{ $product->status }}</td>
                        <td data-field="id">{{ $product->parent->CurrentNameLang }}</td>
                        <td data-field="id"></td>

                        <td colspan="2">
                            @foreach ($product->subSettings as $filter)
                                <input class="form-check-input m-1" type="checkbox"
                                    style="border-radius: 42px;height: 18px;" value="{{ $filter->id }}"
                                    name="category[]" checked>
                                <label class="form-check-label l-5">
                                    {{ $filter->name }}
                                </label>
                            @endforeach
                        </td>

                    @empty
                        <td colspan="6">
                            لا يوجد منتجات لعرضها
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <!-- end tbody -->
        </table>
        <!-- end table -->
        {{ $products->links() }}
    </div>
</div>
