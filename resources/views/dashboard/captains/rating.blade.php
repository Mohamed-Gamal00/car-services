@extends('dashboard.index')
@section('title', ' التقييم')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item"> تقييم كابتن</li>
@endsection

@section('section')
    <style>
        .profile-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; }
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px 80px; position: relative; }
        .profile-avatar { width: 120px; height: 120px; border: 5px solid white; border-radius: 50%; margin: 0 auto; display: block; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .profile-name { font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin-top: 15px; }
        .profile-status { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; }
        .status-available { background: #d4edda; color: #155724; }
        .status-busy { background: #f8d7da; color: #721c24; }
        .rating-section { background: linear-gradient(135deg, #fff9e6 0%, #ffe8cc 100%); border-radius: 12px; padding: 25px; margin-top: 20px; text-align: center; }
        .rating-value { font-size: 3rem; font-weight: 700; color: #f39c12; margin: 10px 0; }
        .rating-label { color: #6c757d; font-size: 1rem; font-weight: 600; }
        .section-card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-radius: 12px; margin-bottom: 25px; }
        .section-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 20px; border-radius: 12px 12px 0 0; display: flex; align-items: center; gap: 10px; }
        .section-header i { font-size: 1.5rem; }
        .section-header h5 { margin: 0; font-weight: 600; }
        .table-modern { margin-bottom: 0; }
        .table-modern thead th { background: #f8f9fa; color: #495057; font-weight: 600; border: none; padding: 15px; }
        .table-modern tbody tr { transition: all 0.3s; border-bottom: 1px solid #f0f0f0; }
        .table-modern tbody tr:hover { background: #f8f9fa; transform: translateX(-3px); }
        .table-modern tbody td { padding: 15px; vertical-align: middle; border: none; }
        .btn-view { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; transition: all 0.3s; }
        .btn-view:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); color: white; }
        .empty-state { text-align: center; padding: 40px 20px; color: #6c757d; }
        .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 15px; }
        .star-rating { display: inline-flex; gap: 3px; font-size: 1.2rem; }
        .star-rating i { color: #f39c12; }
        .comment-text { color: #6c757d; font-style: italic; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .avatar-circle { width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; }
    </style>

    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <div class="card profile-card sticky-top" style="top: 20px;">
                <div class="profile-header"></div>
                <div class="card-body text-center" style="margin-top: -60px;">
                    <img src="{{ $captain->image_url }}" alt="{{$captain->name}}" class="profile-avatar">
                    
                    <h4 class="profile-name">{{$captain->name}} {{$captain->last_name}}</h4>
                    
                    <span class="profile-status {{ $captain->status == 'available' ? 'status-available' : 'status-busy' }}">
                        <i class="mdi mdi-{{ $captain->status == 'available' ? 'check-circle' : 'clock-alert' }}"></i>
                        {{$captain->status == 'available' ? 'متاح' : 'مشغول'}}
                    </span>

                    <div class="rating-section">
                        <div class="rating-label">التقييم العام</div>
                        <div class="rating-value">
                            <i class="mdi mdi-star"></i>
                            {{ number_format($captain->averageRating(), 1) }}
                        </div>
                        <div class="rating-star">
                            <input type="hidden" value="{{$captain->averageRating()}}" class="rating"
                                   data-filled="mdi mdi-star text-warning"
                                   data-empty="mdi mdi-star-outline text-muted" data-readonly/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-8 col-lg-7">
            <div class="card section-card">
                <div class="section-header">
                    <i class="mdi mdi-star-circle"></i>
                    <h5>تقييمات الطلبات</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم العميل</th>
                                    <th>التقييم</th>
                                    <th>التعليق</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($completed_orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->number }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle">{{ substr($order->user->first_name, 0, 1) }}</div>
                                                {{ $order->user->first_name }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($order->rating && $order->rating->stars)
                                                <div class="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $order->rating->stars)
                                                            <i class="mdi mdi-star"></i>
                                                        @else
                                                            <i class="mdi mdi-star-outline" style="color: #ddd;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            @else
                                                <span class="text-muted">لا يوجد تقييم</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->rating && $order->rating->comment)
                                                <span class="comment-text" title="{{ $order->rating->comment }}">
                                                    {{ $order->rating->comment }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">
                                                <i class="mdi mdi-eye"></i> مشاهدة
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="mdi mdi-star-outline"></i>
                                                <p class="mb-0">لا يوجد تقييمات</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($completed_orders->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $completed_orders->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Bootstrap rating js -->
    <script src="{{ asset('assets/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/rating-init.js') }}"></script>
@endpush