@extends('dashboard.index')
@section('title', ' التقييم')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('captains.index')}}">الموظفين</a></li>
    <li class="breadcrumb-item"> تقييم كابتن</li>
@endsection

@section('section')

    <div class="row">
        <div class="col-xl-4">
            <div class="user-sidebar">
                <div class="card" style="background-color: #f8f9fa">
                    <div class="card-body p-1">

                        <div class="mt-n4 position-relative">
                            <div class="text-center">
                                <img src="{{ $captain->image_url }}" alt=""
                                     class="avatar-xl rounded-circle img-thumbnail">

                                <div class="mt-3">
                                    <h5 class="">{{$captain->name}}  {{$captain->last_name}}</h5>
                                    <div>
                                        <a href="#"
                                           class="text-muted m-1">{{$captain->status == 'available' ? 'متاح' : 'مشغول' }}</a>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="p-3 mt-3">
                            <div class="p-4 text-center">
                                <h5 class="font-size-15">التقييم العام </h5>
                                <div class="rating-star">
                                    <input type="hidden" value="{{$captain->averageRating()}}" class="rating"
                                           data-filled="mdi mdi-star text-warning"
                                           data-empty="mdi mdi-star-outline text-muted" data-readonly/>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div>
        </div>

        <div class="col-xl-8">
            <div class="tab-pane active" id="about" role="tabpanel">
                <div>
                    <h5 class="font-size-16 mb-4">الطلبات المكتملة</h5>
                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="country-table">
                            <thead>
                            <tr>
                                <th class="fw-bold">رقم الطلب</th>
                                <th class="fw-bold">اسم العميل</th>
                                <th class="fw-bold">التقييم</th>
                                <th class="fw-bold">التعليق</th>
                                <th class="fw-bold">مشاهدة</th>
                            </tr>
                            </thead>


                            <tbody>
                            @forelse ($completed_orders as $order)
                                <tr data-id="5">
                                    <td data-field="id">{{ $order->number }}</td>
                                    <td>{{ $order->user->first_name }}</td>
                                    {{-- <td data-field="id">{{ $order->addresses->first()->first_name }} {{ $order->addresses->first()->last_name }}</td> --}}

                                    <td data-field="id">
                                        <div class="rating-star">
                                            <input type="hidden" value="{{ $order->rating->stars ?? ''}}" class="rating"
                                                   data-filled="mdi mdi-star text-warning"
                                                   data-empty="mdi mdi-star-outline text-muted" data-readonly/>
                                        </div>

                                    </td>
                                    <td data-field="id">{{ $order->rating->comment ?? ''}}</td>


                                    <td style="width: 2%;text-align-last: center;">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="btn btn-secondary btn-sm edit" title="مشاهدة">
                                            <i class="ion ion-md-eye"></i>
                                        </a>
                                    @empty
                                        <td colspan="10">
                                            لا يوجد طلبات لعرضها
                                        </td>
                                </tr>
                            @endforelse
                        </table>
                        <!-- end table -->
                        {{ $completed_orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <!-- Bootstrap rating js -->
    <script src="{{ asset('assets/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/rating-init.js') }}"></script>
@endpush