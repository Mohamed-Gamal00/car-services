@extends('dashboard.index')
@section('title', 'الخدمات الاضافية')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page"> الخدمات الاضافية</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='dark'/>
                    <x-alert type='danger'/>


                    @can('category.create')
                        <div class="button-items text-end">
                            <a href="{{ route('main_choices.create') }}"
                               class="btn btn-primary waves-effect waves-light">إنشاء
                                خدمة
                                جديد</a>
                        </div>
                    @endcan


                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered"
                                id="product-table">
                            <thead>
                            <tr>
                                <th>اسم الخدمة</th>
                                <th>سعر الخدمة</th>
                                <th>تعديل</th>
                                <th>حذف</th>
                            </tr>
                            </thead>


                            <tbody>
                            @forelse ($choices as $choice)
                                <tr data-id="5">

                                    <td data-field="id">{{ $choice->name }}</td>
                                    <td data-field="id">{{ $choice->service_price }}</td>

                                    @can('admin.edit')
                                        <td style="width: 7%;">
                                            <a href="{{ route('main_choices.edit', $choice->id) }}"
                                               style="font-size: 12px" ;
                                               class="btn btn-primary waves-effect waves-light" title="تعديل">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </td>
                                    @endcan

                                    <form action="{{ route('main_choices.destroy', $choice->id) }}" method="post"
                                          id="formDelete_{{ $choice->id }}">
                                        @csrf
                                        @method('delete')
                                        <td style="width: 7%;">
                                            <button style="font-size: 12px;"
                                                    class="btn btn-danger waves-effect waves-light" title="حذف"
                                                    type="button" onclick="confirmDelete({{ $choice->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </form>

                                    @empty
                                        <td colspan="6">
                                            لا يوجد بنرات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                            </tbody>

                            <!-- end tbody -->
                        </table>
                        <!-- end table -->
                        {{ $choices->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
