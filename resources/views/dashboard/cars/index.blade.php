@extends('dashboard.index')
@section('title', 'السيارات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">السيارات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>
                    @can('country.create')
                        <div class="button-items text-end mb-4">
                            <a type="submit" href="{{ route('cars.create') }}"
                               class="btn btn-primary waves-effect waves-light">إضافة سيارة جديده</a>
                        </div>
                    @endcan

                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="country-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>الماركة (عربي)</th>
                                <th>الماركة (English)</th>
                                <th>الموديل (عربي)</th>
                                <th>الموديل (English)</th>
                                <th>السنة</th>
                                <th>اللون</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($cars as $car)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $car->brand_ar }}</td>
                                    <td>{{ $car->brand_en ?? '-' }}</td>
                                    <td>{{ $car->model_ar }}</td>
                                    <td>{{ $car->model_en ?? '-' }}</td>
                                    <td>{{ $car->year ?? '-' }}</td>
                                    <td>{{ $car->color ?? '-' }}</td>
                                    <td>
                                        @if($car->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('car.edit')
                                            <a href="{{ route('cars.edit', $car->id) }}"
                                               class="btn btn-sm btn-primary" title="تعديل">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('car.delete')
                                            <form method="post" id="formDelete_{{ $car->id }}"
                                                  action="{{ route('cars.destroy', $car->id) }}" style="display: inline;">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-danger" title="حذف"
                                                        type="button" onclick="confirmDelete({{ $car->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        لا يوجد سيارات لعرضها
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- end table -->
                        {{ $cars->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection