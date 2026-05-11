@extends('dashboard.index')
@section('title', 'العملاء')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">العملاء</li>
@endsection

@section('section')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <x-alert type='success'/>
                    <x-alert type='dark'/>
                    <x-alert type='danger'/>
                    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
                        <x-form.input name="phone" placeholder="بحث برقم الجوال" class="mx-2"
                                      :value="request('phone')"/>

                        <button class="btn btn-dark">بحث</button>
                    </form>

                    <div class="table-responsive mt-2">

                        <table
                                class="table table-editable table-nowrap align-middle table-edits table-striped table-bordered mt-2"
                                id="product-table">
                            <thead>

                            <tr>
                                <th>اسم العميل</th>
                                <th>رقم الجوال</th>
                                {{--                                <th>البريد الالكتروني</th>--}}
                                <th>تاريخ الانشاء</th>
                                <th>تعديل</th>
                                <th>مشاهدة</th>
                            </tr>

                            </thead>


                            <tbody>
                            @forelse ($clients as $client)
                                <tr data-id="5">

                                    <td data-field="name">{{ $client->name }}</td>
                                    <td data-field="phone_number">
                                        {{$client->phone }}
                                    </td>
                                    {{--                                    <td data-field="email">{{ $client->email }}</td>--}}
                                    <td data-field="gender">{{ $client->created_at->format('Y-m-d H:i') }}</td>

                                    @can('client.edit')
                                        <td style="width: 7%;">
                                            <a href="{{ route('clients.edit', $client->id) }}" style="font-size: 12px" ;
                                               class="btn btn-primary waves-effect waves-light" title="تعديل">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </td>
                                    @endcan


                                    <td style="width: 7%;">
                                        <a href="{{ route('clients.show', $client->id) }}" style="font-size: 12px" ;
                                           class="btn btn-primary waves-effect waves-light" title="طلبات العميل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>


                                    {{--                                    @can('client.delete')--}}
                                    {{--                                        <form method="post" action="{{ route('clients.destroy', $client->id) }}"--}}
                                    {{--                                              id="formDelete_{{$client->id}}">--}}
                                    {{--                                            @csrf--}}
                                    {{--                                            @method('delete')--}}
                                    {{--                                            <td style="width: 7%;">--}}
                                    {{--                                                <button style="font-size: 12px;"--}}
                                    {{--                                                        class="btn btn-danger waves-effect waves-light" title="حذف"--}}
                                    {{--                                                        type="button" onclick="confirmDelete({{$client->id}})">--}}
                                    {{--                                                    <i class="fas fa-trash-alt"></i>--}}
                                    {{--                                                </button>--}}
                                    {{--                                            </td>--}}
                                    {{--                                        </form>--}}
                                    {{--                                    @endcan--}}
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
                        {{ $clients->withQueryString()->links() }}
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
