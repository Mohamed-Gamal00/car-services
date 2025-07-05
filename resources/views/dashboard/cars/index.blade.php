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
                                <th>نوع السيارة</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($cars as $car)
                                <tr data-id="5">
                                    <td data-field="id">{{ $car->name_ar }}</td>
                                    @can('country.edit')
                                        <td style="width: 5%;">
                                            <a href="{{ route('cars.edit', $car->id) }}"
                                               class="btn btn-primary waves-effect waves-light" title="تعديل">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </td>
                                    @endcan
                                    @can('country.delete')
                                        <form method="post" id="formDelete_{{ $car->id }}"
                                              action="{{ route('cars.destroy', $car->id) }}">
                                            @csrf
                                            @method('delete')
                                            <td style="width: 7%;">
                                                <button style="font-size: 12px;"
                                                        class="btn btn-danger waves-effect waves-light" title="حذف"
                                                        type="button" onclick="confirmDelete({{ $car->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </form>
                                    @endcan
                                    @empty
                                        <td colspan="6">
                                            لا يوجد سيارات لعرضها
                                        </td>
                                </tr>
                            @endforelse
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