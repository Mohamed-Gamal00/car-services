@extends('dashboard.index')
@section('title', 'إدارة الخدمات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">الخدمات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">قائمة الخدمات</h4>
                        <div class="button-items">
                            @can('service.trash.view')
                                <a href="{{ route('services.trash') }}" class="btn btn-secondary waves-effect waves-light">
                                    <i class="fas fa-trash-restore me-1"></i> سلة المحذوفات
                                </a>
                            @endcan
                            @can('service.create')
                                <a href="{{ route('services.create') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="fas fa-plus me-1"></i> إضافة خدمة جديدة
                                </a>
                            @endcan
                        </div>
                    </div>

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>

                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-striped table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">الصورة</th>
                                <th>اسم الخدمة</th>
                                <th>الوصف</th>
                                <th style="width: 10%;">المدة</th>
                                <th style="width: 10%;">السعر</th>
                                <th style="width: 10%;">الحالة</th>
                                <th style="width: 10%;">الترتيب</th>
                                <th style="width: 15%;">الإجراءات</th>
                            </tr>
                            </thead>

                            @if (!$services->isEmpty())
                                <tbody>
                                @foreach ($services as $index => $service)
                                    <tr>
                                        <td>{{ $services->firstItem() + $index }}</td>
                                        <td>
                                            <img alt="{{ $service->name }}" class="img-thumbnail rounded" 
                                                 width="60" height="60"
                                                 src="{{ $service->image_url }}">
                                        </td>
                                        <td>
                                            <strong>{{ $service->name }}</strong>
                                            @if($service->name_en)
                                                <br><small class="text-muted">{{ $service->name_en }}</small>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($service->description, 60) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $service->duration }}</span>
                                            <br><small class="text-muted">{{ $service->getDurationInMinutes() }} دقيقة</small>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($service->price, 2) }} ريال</strong>
                                        </td>
                                        <td>
                                            @if ($service->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>{{ $service->sort_order ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('service.edit')
                                                    <a href="{{ route('services.edit', $service->id) }}"
                                                       class="btn btn-sm btn-primary" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('service.delete')
                                                    <form method="post" id="formDelete_{{ $service->id }}"
                                                          action="{{ route('services.destroy', $service->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-danger" title="حذف"
                                                                type="button" onclick="confirmDelete({{ $service->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا يوجد خدمات لعرضها</p>
                                        @can('service.create')
                                            <a href="{{ route('services.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> إضافة خدمة جديدة
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                عرض {{ $services->firstItem() ?? 0 }} إلى {{ $services->lastItem() ?? 0 }} من أصل {{ $services->total() }} خدمة
                            </div>
                            <div>
                                {{ $services->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('هل أنت متأكد من حذف هذه الخدمة؟\nسيتم نقلها إلى سلة المحذوفات ويمكن استعادتها لاحقاً.')) {
            document.getElementById('formDelete_' + id).submit();
        }
    }
</script>
@endsection
