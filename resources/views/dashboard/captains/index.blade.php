@extends('dashboard.index')
@section('title', 'الموظفين')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">الموظفين</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='dark'/>
                    <x-alert type='danger'/>
                    <div class="button-items text-end ">
                        <a type="submit" href="{{ route('captains.create') }}"
                           class="btn btn-primary waves-effect waves-light">اضافة موظف جديد</a>
                    </div>
                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="product-table">
                            <thead>

                            <tr>
                                <th>اسم الكابتن</th>
                                <th>رقم الجوال</th>
                                <th>حالة العضوية</th>
                                <th>حالة العمل</th>
                                <th>تاريخ الانشاء</th>
                                <th>تعديل</th>
                                <th>مشاهدة</th>
                                <th>تقييمات</th>
                                {{--                                <th>حذف</th>--}}
                            </tr>

                            </thead>


                            <tbody>
                            @forelse ($captains as $captain)
                                <tr data-id="5">

                                    <td data-field="name">{{ $captain->name . ' ' . $captain->last_name }}</td>
                                    <td data-field="phone">{{$captain->phone }}</td>
                                    <td data-field="phone_number">{{$captain->status == 'available' ? 'متاح' : 'مشغول' }}</td>
                                    <td data-field="phone_number">{{$captain->is_active == 0 ?'غير نشط' : 'نشط' }}</td>
                                    {{--                                    <td data-field="email">{{ $captain->email }}</td>--}}
                                    <td data-field="gender">{{ $captain->created_at->format('Y-m-d H:i') }}</td>

                                    <td style="width: 7%;">
                                        <a href="{{ route('captains.edit', $captain->id) }}" style="font-size: 12px"

                                           class="btn btn-primary waves-effect waves-light" title="تعديل">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>

                                    <td style="width: 7%;">
                                        <a href="{{ route('captains.show', $captain->id) }}"
                                           style="font-size: 12px;margin: 0px 8px;"

                                           class="btn btn-primary waves-effect waves-light" title="مشاهدة">
                                            <i class="ion ion-md-eye"></i>
                                        </a>
                                    </td>

                                    <td style="width: 7%;">
                                        <a href="{{ route('captain.rating', $captain->id) }}"
                                           style="font-size: 12px;margin: 0px 8px;"

                                           class="btn btn-primary waves-effect waves-light" title="التقييم">
                                            <i class="ion ion-md-star"></i>
                                        </a>
                                    </td>

                                    {{--                                    <form method="post" action="{{ route('captains.destroy', $captain->id) }}"--}}
                                    {{--                                          id="formDelete_{{$captain->id}}">--}}
                                    {{--                                        @csrf--}}
                                    {{--                                        @method('delete')--}}
                                    {{--                                        <td style="width: 7%;">--}}
                                    {{--                                            <button style="font-size: 12px;"--}}
                                    {{--                                                    class="btn btn-danger waves-effect waves-light" title="حذف"--}}
                                    {{--                                                    type="button" onclick="confirmDelete({{$captain->id}})">--}}
                                    {{--                                                <i class="fas fa-trash-alt"></i>--}}
                                    {{--                                            </button>--}}
                                    {{--                                        </td>--}}
                                    {{--                                    </form>--}}
                                    @empty
                                        <td colspan="6">
                                            لا يوجد عملاء لعرضهم
                                        </td>
                                </tr>
                            @endforelse
                            </tbody>

                            <!-- end tbody -->
                        </table>
                        <!-- end table -->
                        {{ $captains->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
