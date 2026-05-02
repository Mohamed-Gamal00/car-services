@extends('dashboard.index')
@section('title', 'سلة المحذوفات - الخدمات')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">الخدمات</a></li>
    <li class="breadcrumb-item active">سلة المحذوفات</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-trash-restore me-2"></i> سلة المحذوفات - الخدمات
                        </h4>
                        <div class="button-items">
                            <a href="{{ route('services.index') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="fas fa-arrow-left me-1"></i> العودة للخدمات
                            </a>
                        </div>
                    </div>

                    <x-alert type='success'/>
                    <x-alert type='danger'/>
                    <x-alert type='dark'/>

                    @if (!$services->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>تنبيه:</strong> الخدمات المحذوفة يمكن استعادتها أو حذفها نهائياً. الحذف النهائي لا يمكن التراجع عنه.
                        </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-striped table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">الصورة</th>
                                <th>اسم الخدمة</th>
                                <th style="width: 10%;">السعر</th>
                                <th style="width: 15%;">تاريخ الحذف</th>
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
                                        <td>
                                            <strong class="text-success">{{ number_format($service->price, 2) }} ريال</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $service->deleted_at->diffForHumans() }}
                                                <br>
                                                {{ $service->deleted_at->format('Y-m-d H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('service.restore')
                                                    <form method="post" id="formRestore_{{ $service->id }}"
                                                          action="{{ route('services.restore', $service->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-sm btn-success" title="استعادة"
                                                                type="button" onclick="confirmRestore({{ $service->id }})">
                                                            <i class="fas fa-undo"></i> استعادة
                                                        </button>
                                                    </form>
                                                @endcan
                                                @can('service.delete.forever')
                                                    <form method="post" id="formForceDelete_{{ $service->id }}"
                                                          action="{{ route('services.force-delete', $service->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="حذف نهائي"
                                                                type="button" onclick="confirmForceDelete({{ $service->id }})">
                                                            <i class="fas fa-trash-alt"></i> حذف نهائي
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
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">سلة المحذوفات فارغة</p>
                                        <a href="{{ route('services.index') }}" class="btn btn-primary">
                                            <i class="fas fa-arrow-left me-1"></i> العودة للخدمات
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                        
                        @if (!$services->isEmpty())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    عرض {{ $services->firstItem() ?? 0 }} إلى {{ $services->lastItem() ?? 0 }} من أصل {{ $services->total() }} خدمة محذوفة
                                </div>
                                <div>
                                    {{ $services->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function confirmRestore(id) {
        if (confirm('هل أنت متأكد من استعادة هذه الخدمة؟')) {
            document.getElementById('formRestore_' + id).submit();
        }
    }

    function confirmForceDelete(id) {
        if (confirm('⚠️ تحذير: هل أنت متأكد من الحذف النهائي؟\n\nلن تتمكن من استعادة هذه الخدمة مرة أخرى!\nسيتم حذف جميع الصور والبيانات المرتبطة بها نهائياً.')) {
            if (confirm('تأكيد نهائي: هل أنت متأكد 100%؟')) {
                document.getElementById('formForceDelete_' + id).submit();
            }
        }
    }
</script>
@endsection
