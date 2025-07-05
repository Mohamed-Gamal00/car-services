@extends('dashboard.index')

@push('styles')
    <!-- Bootstrap datatable js -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css">

    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet"
          type="text/css">
@endpush

@section('title', 'التقارير')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('reports.index') }}">
            التقارير
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        <a href="">
            كود الخصم
        </a>
    </li>
@endsection

@section('section')
    <x-alert type="success"/>
    <div>
        <div class="container">
            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                <thead>
                <tr>
                    <th>اسم كود الخصم</th>
                    <th>الكود</th>
                    <th>الخصم</th>
                    <th>عدد مرات الاستخدام المتاحه</th>
                    <th>الحالة</th>

                </tr>
                </thead>

                <tbody>
                @forelse ($discounts as $code)
                    <tr data-id="5">
                        <td data-field="id">{{ $code->name }}</td>
                        <td data-field="id">{{ $code->code }}</td>
                        <td data-field="id">{{ $code->price }}</td>
                        <td data-field="id">{{ $code->number_of_used }}</td>
                        <td data-field="id">{{ $code->status == 'active' ? 'نشط' : 'غير نشط' }}</td>


                        @empty
                            <td colspan="6" class="text center">
                                لا يوجد بيانات لعرضها
                            </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $discounts->withQueryString()->links() }}
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                paging: false, // Disable DataTables pagination
                searching: true, // Enable searching
                ordering: true, // Enable column ordering
                info: false, // Disable DataTables' "Showing X to Y of Z entries"
                order: [[0, 'desc']], // Order by the first column (created_at) in descending order
                columnDefs: [
                    {
                        targets: 0, // The column index for "created_at"
                        type: 'date' // Ensure it recognizes the date format for proper sorting
                    }
                ]
            });
        });
    </script>
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
