@extends('dashboard.index')
@section('title', 'صفحات التطبيق')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">صفحات التطبيق</li>
@endsection

@section('section')
    <style>
        .pages-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; }
        .pages-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; display: flex; align-items: center; gap: 15px; }
        .pages-header i { font-size: 2rem; }
        .pages-header h4 { margin: 0; font-weight: 600; }
        .table-modern { margin-bottom: 0; }
        .table-modern thead th { background: #f8f9fa; color: #495057; font-weight: 600; border: none; padding: 15px 20px; }
        .table-modern tbody tr { transition: all 0.3s; border-bottom: 1px solid #f0f0f0; }
        .table-modern tbody tr:hover { background: #f8f9fa; transform: translateX(-3px); }
        .table-modern tbody td { padding: 20px; vertical-align: middle; border: none; }
        .page-title { font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 10px; }
        .page-title i { color: #667eea; font-size: 1.3rem; }
        .btn-edit { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); color: white; }
        .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
        .empty-state i { font-size: 4rem; opacity: 0.3; margin-bottom: 20px; }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card pages-card">
                <div class="pages-header">
                    <i class="mdi mdi-file-document-multiple"></i>
                    <div>
                        <h4>صفحات التطبيق</h4>
                        <p class="mb-0" style="opacity: 0.9; font-size: 0.9rem;">إدارة محتوى الصفحات الثابتة</p>
                    </div>
                </div>

                <div class="card-body p-0">
                    <x-alert type='success'/>
                    <x-alert type='dark'/>

                    @if($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>عنوان الصفحة</th>
                                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $page)
                                        <tr>
                                            <td><strong>{{ $page->id }}</strong></td>
                                            <td>
                                                <div class="page-title">
                                                    <i class="mdi mdi-file-document"></i>
                                                    {{ $page->title }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                    تعديل
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="mdi mdi-file-document-multiple-outline"></i>
                            <p class="mb-0">لا توجد صفحات متاحة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
