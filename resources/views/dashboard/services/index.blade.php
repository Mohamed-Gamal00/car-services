@extends('dashboard.index')
@section('title', 'الخدمات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الخدمات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>
                    @can('product.create')
                        <div class="button-items text-end mb-4">
                            <a type="submit" href="{{ route('services.create') }}"
                               class="btn btn-primary waves-effect waves-light">إنشاء خدمة جديدة</a>
                        </div>
                    @endcan

                    <div class="table-responsive mt-2">
                        <table class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2">
                            <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>اسم الخدمة</th>
                                <th>الوصف</th>
                                <th>المدة</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>تعديل</th>
                                <th>حذف</th>
                            </tr>
                            </thead>

                            @if (!$services->isEmpty())
                                <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td style="width: 7%;">
                                            <img alt="" class="img-thumbnail rounded me-2" width="50" height="50"
                                                 src="{{ $service->image_url ?? asset('assets/images/default-service.png') }}">
                                        </td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ Str::limit($service->description, 50) }}</td>
                                        <td>{{ $service->duration }} دقيقة</td>
                                        <td>{{ $service->price }} ريال</td>
                                        <td>
                                            @if ($service->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        @can('product.edit')
                                            <td style="width: 5%;">
                                                <a href="{{ route('services.edit', $service->id) }}"
                                                   class="btn btn-primary waves-effect waves-light" title="تعديل">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('product.delete')
                                            <td style="width: 7%;">
                                                <form method="post" id="formDelete_{{ $service->id }}"
                                                      action="{{ route('services.destroy', $service->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button style="font-size: 12px;"
                                                            class="btn btn-danger waves-effect waves-light" title="حذف"
                                                            type="button" onclick="confirmDelete({{ $service->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="8" class="text-center">لا يوجد خدمات لعرضها</td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('هل أنت متأكد من حذف هذه الخدمة؟')) {
            document.getElementById('formDelete_' + id).submit();
        }
    }
</script>
@endsection
