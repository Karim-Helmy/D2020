<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">


            <!-- Main Page -->
            <li class="nav-item"><a href="{{ aurl('/index') }}"><i class="la la-home"></i>
                <span class="menu-title" data-i18n="nav.dash.main">{{ trans('admin.dashboard') }}</span></a>
            </li>
            @if (userCan('messages'))
                <!-- Messages From Land Page -->
                <li class=" nav-item"><a href="{{ aurl('/contacts') }}"><i class="icon-envelope-letter"></i>
                    <span class="menu-title" data-i18n="nav.dash.main">{{ trans('admin.visit_messages') }}</span><span class="badge badge badge-pill badge-danger float-right mr-2">{{CountMessageVisitors()}}</span></a>
                </li>

                <!-- Messages From Supervisor -->
                <li class=" nav-item"><a href="{{ aurl('/messages') }}"><i class="icon-envelope-letter"></i>
                    <span class="menu-title" data-i18n="nav.dash.main">{{ trans('admin.messages') }}</span><span class="badge badge badge-pill badge-danger float-right mr-2">{{ CountMessageAdmin() }}</span></a>
                </li>
            @endif

            <!-- Other Links In Menu -->
            <li class=" navigation-header">
                <span data-i18n="nav.category.support">{{ trans('admin.Menu') }}</span><i class="la la-ellipsis-h ft-minus" data-toggle="tooltip"
                data-placement="right" data-original-title="Support"></i>
            </li>

            @if (userCan('admin'))
                <!-- Admins -->
                <li class="nav-item"><a href="#"><i class="fas fa-user-secret"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.admins') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/admins') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all admins') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/admins/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.create new admin') }}</a></li>
                    </ul>
                </li>
            @endif

            @if (userCan('categories'))
                <!-- Categories -->
                <li class="nav-item"><a href="#"><i class="la la-bars"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.Categories') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/categories') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all category') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/categories/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.add category') }}</a></li>
                    </ul>
                </li>
            @endif
        @if (userCan('cities'))
            <!-- Categories -->
                <li class="nav-item"><a href="#"><i class="la la-sitemap"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.Cities') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/cities') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all city') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/cities/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.add city') }}</a></li>
                    </ul>
                </li>
        @endif
        @if (userCan('subscribers'))
            <!-- Subscribers -->
                <li><a class="menu-item" href="#" data-i18n="nav.templates.horz.main"><i class="la la-users"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.subscribers') }}</span><span class="badge badge badge-pill badge-danger float-right mr-2">{{CountSupervisor()}}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/subscribers/create') }}" data-i18n="nav.templates.horz.top_icon">{{ trans('admin.add subscriber') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/subscribers') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.subscribers') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/subscribers?status=1') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.subsciber_agree') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/subscribers?status=0') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.subsciber_waiting1') }}<span class="badge badge badge-pill badge-danger float-right mr-2" style="margin-left:0; margin-right:0;">{{CountSupervisor()}}</span></a></li>
                        </li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#"><i class="la la-hotel"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.Stages') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/stages') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all stage') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/stages/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.add stage') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#"><i class="la la-shopping-cart"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.products') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/products') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all product') }}</a></li>
                        <li><a class="menu-item" href="{{ aurl('/products/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.add product') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#"><i class="la la-bell-o"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.orders') }}</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{ aurl('/orders') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all order') }}</a></li>
                    </ul>
                </li>
        @endif

        @if (userCan('partners'))
            <!-- Logos -->
            <li class="nav-item"><a href="#"><i class="ft ft-image"></i><span class="menu-title" data-i18n="nav.templates.main">{{ trans('admin.Logos') }}</span></a>
                <ul class="menu-content">
                    <li><a class="menu-item" href="{{ aurl('/logos') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.all logo') }}</a></li>
                    <li><a class="menu-item" href="{{ aurl('/logos/create') }}" data-i18n="nav.templates.horz.classic">{{ trans('admin.add logo') }}</a></li>
                </ul>
            </li>
        @endif



            <!-- Subscribers -->

        @if (userCan('settings'))
            <!-- Settings -->
            <li class=" navigation-header">
                <span data-i18n="nav.category.support">{{ trans('admin.settings') }}</span><i class="la la-ellipsis-h ft-minus" data-toggle="tooltip"
                data-placement="right" data-original-title="Support"></i>
            </li>

            <li class=" nav-item">
                <a href="{{ aurl('/settings') }}"><i class="icon-settings"></i>
                    <span class="menu-title" data-i18n="nav.support_documentation.main">{{ trans('admin.settings') }}</span>
                </a>
            </li>
        @endif

    </ul>

</div>
</div>
