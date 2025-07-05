<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <a href="{{ route('dashboard.index') }}">
            <li class="menu-title title">لوحة التحكم</li>
        </a>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ti-home"></i>
                <span>واجهة التطبيق</span>
            </a>


            <ul class="sub-menu" aria-expanded="false">
                @can('product.view')
                    <li><a href="{{ route('products.index') }}">الخدمات</a></li>
                @endcan
                {{--                @can('product.view')--}}
                {{--                    <li><a href="{{ route('out_of_stock') }}">منتجات قاربت على النفاذ</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('category.view')--}}
                {{--                    <li><a href="{{ route('main_categories.index') }}">الاقسام</a></li>--}}
                {{--                @endcan--}}
                @can('product.view')
                    <li><a href="{{ route('main_choices.index') }}">الخدمات الاضافية</a></li>
                @endcan

                <li><a href="{{ route('packages.index') }}">الباقات</a></li>
                {{--                @can('product_setting.view')--}}
                {{--                    <li><a href="{{ route('products_settings.index') }}">خيارات المنتج</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('filter.view')--}}
                {{--                    <li><a href="{{ route('filters.index') }}">التصنيفات</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('color.view')--}}
                {{--                    <li><a href="{{ route('colors.index') }}">الألوان</a></li>--}}
                {{--                @endcan--}}
            </ul>
        </li>


        @can('captain.view')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-users"></i> <span>قائمة الموظفين</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">

                    <li><a href="{{ route('captains.index') }}">الموظفين</a></li>

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
                <span>التصميم</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                {{--                @can('design.view')--}}
                {{--                    <li><a href="{{ route('designs.index') }}">البنرات</a></li>--}}
                {{--                @endcan--}}
                @can('design.view')
                    <li><a href="{{ route('designs.index') }}">البنرات المتحركة</a></li>
                @endcan

            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                {{--                @if ($unreadOrderCreatedCount > 0)--}}
                {{--                    <span class="badge rounded-pill bg-danger float-end">{{ $unreadOrderCreatedCount }}</span>--}}
                {{--                @endif--}}
                <i class="far fa-credit-card"></i> <span>الحجوزات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('order.view')
                    <li><a href="{{ route('orders.index') }}">الطلبات</a></li>
                @endcan
                {{--                    <li><a href="{{ route('return_orders.index') }}">طلبات ملغية</a></li>--}}
                {{--                @endcan--}}
                <li><a href="{{ route('payments.index') }}">المدفوعات</a></li>
                @can('order.view')
                    {{--                    <li><a href="{{ route('bulk_orders.index') }}">طلبات الشراء بالجملة</a></li>--}}
                @endcan
                {{--                @can('order.view')--}}
                {{--                    <li><a href="{{ route('representatives_orders.index') }}">طلبات المناديب</a></li>--}}
                {{--                @endcan--}}
            </ul>
        </li>


        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fas fa-users"></i> <span>قائمة العملاء</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('client.view')
                    <li><a href="{{ route('clients.index') }}">العملاء</a></li>
                @endcan
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="fas fa-cogs"></i> <span>الضبط</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @can('settings.edit')
                    <li><a href="{{ route('settings') }}">الاعدادات</a></li>
                @endcan
                {{-- @can('settings.edit')
					<li><a href="{{route('header_text.index')}}">النصوص المتحركه</a></li>
				@endcan --}}
                {{--                @can('settings.edit')--}}
                {{--                    <li><a href="{{ route('advertisements.index') }}">شريط اعلاني متحرك</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('currencies.view')--}}
                {{--                    <li><a href="{{ route('currencies.index') }}">العملات</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('countries.view')--}}
                {{--                    <li><a href="{{ route('countries.index') }}">الدول</a></li>--}}
                {{--                @endcan--}}
                @can('cities.view')
                    <li><a href="{{ route('cities.index') }}">المدن</a></li>
                @endcan
                @can('car.view')
                    <li><a href="{{ route('cars.index') }}">السيارات</a></li>
                @endcan
                {{--                @can('product_availability.view')--}}
                {{--                    <li><a href="{{ route('product_availability.index') }}">حالات التوفر</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('order_status.view')--}}
                {{--                    <li><a href="{{ route('order_status.index') }}">حالة الطلب</a></li>--}}
                {{--                @endcan--}}
                @can('discount_code.view')
                    <li><a href="{{ route('discount_code.index') }}">أكود الخصم</a></li>
                @endcan
                {{--                @can('settings.edit')--}}
                {{--                    <li><a href="{{ route('shipping.index') }}">الشحن</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('settings.edit')--}}
                {{--                    <li><a href="{{ route('shipping_companies.index') }}">شركات الشحن</a></li>--}}
                {{--                @endcan--}}
                {{--                @can('news.view')--}}
                {{--                    <li><a href="{{ route('user_news.create') }}">النشرة البريدية</a></li>--}}
                {{--                @endcan--}}
                {{--                <li><a href="{{ route('store_featuers.index') }}">ميزات المتجر</a></li>--}}
            </ul>
        </li>

        @can('admin.view')
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-users-cog"></i> <span>قائمة المدراء</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    @can('admin.view')
                        <li><a href="{{ route('admins.index') }}">المدراء</a></li>
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
                    <i class="fas fa-mail-bulk"></i> <span>التواصل</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    @can('contact_us.view')
                        <li><a href="{{ route('contact_us.index') }}"> رسائل الاتصال</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-address-book"></i> <span>الصفحات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('pages.index') }}">صفحات التطبيق</a></li>
                {{--                <li><a href="{{ route('common_questions.index') }}">الاسئلة الشائعة</a></li>--}}
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-address-book"></i> <span>الاشعارات</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('notification.Dashboard.create') }}">إشعار العملاء</a></li>
                {{--                <li><a href="{{ route('common_questions.index') }}">الاسئلة الشائعة</a></li>--}}
            </ul>
        </li>


        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="far fa-address-book"></i> <span>التقارير</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('reports.index') }}">صفحة التقارير</a></li>
            </ul>
        </li>

    </ul>
</div>
