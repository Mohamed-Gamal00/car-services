@extends('dashboard.index')
@section('title', 'تعديل صفحة التطبيق')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">صفحات التطبيق</a></li>
    <li class="breadcrumb-item active" aria-current="page">تعديل</li>
@endsection

@section('section')
    <style>
        .form-container { max-width: 1000px; margin: 0 auto; }
        .form-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; }
        .form-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .form-header i { font-size: 3rem; margin-bottom: 10px; }
        .form-header h4 { margin: 0; font-weight: 700; }
        .form-group-modern { margin-bottom: 25px; }
        .form-label-modern { font-weight: 600; color: #495057; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; font-size: 1rem; }
        .form-label-modern i { color: #667eea; font-size: 1.2rem; }
        .page-title-display { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 15px 20px; border-radius: 10px; font-size: 1.1rem; font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 10px; }
        .page-title-display i { color: #667eea; font-size: 1.5rem; }
        .editor-wrapper { border: 2px solid #e9ecef; border-radius: 10px; overflow: hidden; }
        .btn-save { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 40px; border-radius: 25px; color: white; font-weight: 600; transition: all 0.3s; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4); color: white; }
        .btn-back { background: #6c757d; border: none; padding: 12px 40px; border-radius: 25px; color: white; font-weight: 600; transition: all 0.3s; }
        .btn-back:hover { background: #5a6268; transform: translateY(-2px); color: white; }
    </style>

    <div class="form-container">
        <div class="card form-card">
            <div class="form-header">
                <i class="mdi mdi-file-document-edit"></i>
                <h4>تعديل صفحة التطبيق</h4>
                <p class="mb-0 mt-2" style="opacity: 0.9;">تحديث محتوى الصفحة</p>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="title" value="{{ $page->title }}">

                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="mdi mdi-format-title"></i>
                            عنوان الصفحة
                        </label>
                        <div class="page-title-display">
                            <i class="mdi mdi-file-document"></i>
                            {{ $page->title }}
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern" for="content">
                            <i class="mdi mdi-text-box"></i>
                            محتوى الصفحة
                        </label>
                        <div class="editor-wrapper">
                            <textarea id="elm1" name="content">{!! $page->content !!}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <a href="{{ route('pages.index') }}" class="btn btn-back">
                            <i class="mdi mdi-arrow-right me-2"></i>
                            رجوع
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="mdi mdi-content-save me-2"></i>
                            تحديث الصفحة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--tinymce js-->
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection
