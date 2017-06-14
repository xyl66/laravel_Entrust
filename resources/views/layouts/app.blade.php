<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#app-navbar-collapse">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li @if ($sitename[0]) class="active"@endif><a href="#">性能管理</a></li>
                        <li @if ($sitename[1]) class="active"@endif><a href="#">课程列表</a></li>
                        <li @if ($sitename[2]) class="active"@endif><a href="#">新建课程</a></li>
                        @ability('admin','role-edit,role-create,role-delete,user-edit,user-delete') <!-- 根据权限显示视图 -->
                            <li @if ($sitename[3]) class="dropdown active"@else class="dropdown" @endif>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    系统管理 <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    @ability('admin','role-edit,role-create,role-delete') <!-- 根据权限显示视图 -->
                                    <li @if ($sitename[3][0]) class="active"@endif><a href="{{ url('admin/role') }}">角色管理</a></li>
                                    @endability
                                    @role('developer')<!-- 根据角色显示视图 开发者-->
                                    <li @if ($sitename[3][1]) class="active"@endif><a href="{{url('admin/permission')}}">权限管理</a></li>
                                    @endrole
                                    @ability('admin','user-edit,user-list,user-delete') <!-- 根据权限显示视图 -->
                                    <li @if ($sitename[3][2]) class="active"@endif><a href="{{url('admin/user')}}">用户管理</a></li>
                                    @endability
                                </ul>
                            </li>
                        @endability
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
