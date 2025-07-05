@extends('dashboard.index')
@section('title', 'الباقات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الباقات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>

                    <div class="button-items text-end mb-4">
                        <a type="submit" href="{{ route('packages.create') }}"
                           class="btn btn-primary waves-effect waves-light">إانشاء
                            باقة
                            جديد</a>
                    </div> 


                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="product-table">
                            <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>اسم الباقة</th>
                                <th>حالة الباقة</th>
                                <th>السعر</th>
                                <th>تعديل</th>
                                <th>حذف</th>
                            </tr>
                            </thead>


                            @if (!$packages->isEmpty())
                                <tbody>
                                @forelse ($packages as $package)
                                    <tr data-id="5">
                                        <td style="width: 7%;" data-field="id">
                                            <img alt=""
                                                 class="img-thumbnail rounded me-2"
                                                 width="50" height="50"
                                                 src="{{asset('storage/'. $package->icon)  }}"
                                                 data-holder-rendered="true">
                                        </td>
                                        <td data-field="id">{{ $package->name }}</td>
                                        <td data-field="id">
                                            @if ($package->is_active == '1')
                                                نشط
                                            @elseif($package->is_active == '0')
                                                غير نشط
                                            @endif
                                        </td>
                                        <td data-field="name">{{ $package->price }}</td>

                                        <td style="width: 5%;">
                                            <a href="{{ route('packages.edit', $package->id) }}"
                                               class="btn btn-primary waves-effect waves-light" title="تعديل">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </td>


                                        <form method="post" id="formDelete_{{ $package->id }}"
                                              action="{{ route('packages.destroy', $package->id) }}">
                                            @csrf
                                            @method('delete')
                                            <td style="width: 7%;">
                                                <button style="font-size: 12px;"
                                                        class="btn btn-danger waves-effect waves-light" title="حذف"
                                                        type="button" onclick="confirmDelete({{ $package->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </form>

                                        @empty
                                            <td colspan="6">
                                                لا يوجد خدمات لعرضها
                                            </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="9">لا يوجد خدمات لعرضها</td>
                                </tr>
                                </tbody>
                            @endif

                        </table>

                        {{ $packages->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
