<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <a href="{{ route('dashboard.index') }}">
            <li class="menu-title title">لوحة التحكم</li>
        </a>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ti-home"></i>
                <span>الخدمات والباقات</span>
            </a>

            <ul class="sub-menu" aria-expanded="false">
                @can('product.view')
                    <li><a href="{{ route('services.index') }}">الخدمات</a></li>
                @endcan
                @can('product.view')
                    <li><a href="{{ route('main_choices.index') }}">الخدمات الاضافية</a></li>
                @endcan
                <li><a href="{{ route('packages.index') }}">الباقات</a></li>
            </ul>
        </li>


        @can('captain.view')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-user-tie"></i> <span>الموظفين</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ route('captains.index') }}">قائمة الموظفين</a></li>
                </ul>
            </li>
        @endcan
        {{--        <li>--}}
        {{--            <a href="javascript: void(0);" class="has-arrow waves-effect">--}}
        {{--                <i class="fa fa-building"></i>--}}
        {{--                <span>الشركات</span>--}}
        {{--            </a>--}}
        {{--            <ul class="sub-menu" aria-expanded="false">--}}
        {{--                @can('company.create')--}}
        {{--                    <li><a href="{{ route('companies.create') }}">اضافة</a></li>--}}
        {{--                @endcan--}}
        {{--                @can('company.view')--}}
        {{--                    <li><a href="{{ route('companies.index') }}">مشاهدة</a></li>--}}
        {{--                @endcan--}}
        {{--            </ul>--}}
        {{--        </li>--}}

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fa fa-paint-brush"></i>
                <span>البنرات والتصميم</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('design.view')
                    <li><a href="{{ route('designs.index') }}">البنرات المتحركة</a></li>
                @endcan
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-credit-card"></i> <span>الحجوزات والطلبات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('order.view')
                    <li><a href="{{ route('orders.index') }}">الطلبات</a></li>
                @endcan
                <li><a href="{{ route('payments.index') }}">المدفوعات</a></li>
            </ul>
        </li>


        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fas fa-users"></i> <span>العملاء</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('client.view')
                    <li><a href="{{ route('clients.index') }}">قائمة العملاء</a></li>
                @endcan
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fas fa-cogs"></i> <span>الإعدادات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('settings.edit')
                    <li><a href="{{ route('settings') }}">إعدادات التطبيق</a></li>
                @endcan
                @can('cities.view')
                    <li><a href="{{ route('cities.index') }}">المدن</a></li>
                @endcan
                @can('car.view')
                    <li><a href="{{ route('cars.index') }}">السيارات</a></li>
                @endcan
                @can('discount_code.view')
                    <li><a href="{{ route('discount_code.index') }}">أكواد الخصم</a></li>
                @endcan
            </ul>
        </li>

        @can('admin.view')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-users-cog"></i> <span>المدراء</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    @can('admin.view')
                        <li><a href="{{ route('admins.index') }}">قائمة المدراء</a></li>
                    @endcan
                    @can('admin.group.view')
                        <li><a href="{{ route('rules.index') }}">المجموعات</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('contact_us.view')
            <li>
                <a href="javascript: void(0);" class="{{ $unreadMessageCount > 0 ? '' : 'has-arrow' }} waves-effect">
                    @if ($unreadMessageCount > 0)
                        <span class="badge rounded-pill bg-danger float-end">{{ $unreadMessageCount }}</span>
                    @endif
                    <i class="fas fa-envelope"></i> <span>رسائل التواصل</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    @can('contact_us.view')
                        <li><a href="{{ route('contact_us.index') }}">رسائل الاتصال</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-file-alt"></i> <span>الصفحات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('pages.index') }}">صفحات التطبيق</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-bell"></i> <span>الإشعارات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('notification.Dashboard.create') }}">إشعار العملاء</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fas fa-chart-bar"></i> <span>التقارير</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('reports.index') }}">صفحة التقارير</a></li>
            </ul>
        </li>

    </ul>
</div>
