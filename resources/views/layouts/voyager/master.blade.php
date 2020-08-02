<!DOCTYPE html>
<html lang="{{ \Illuminate\Support\Facades\Config::get('app.locale') }}" dir="{{ __('facilitador::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('facilitador.facilitador_assets') }}"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

    <!-- Favicon -->
    <?php $admin_favicon = Facilitador::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ facilitador_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Facilitador::image($admin_favicon) }}" type="image/png">
    @endif



    <!-- App CSS -->
    <link rel="stylesheet" href="{{ facilitador_asset('css/app.css') }}">

    @yield('css')
    @if(__('facilitador::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ facilitador_asset('css/rtl.css') }}">
    @endif

    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .facilitador .side-menu .navbar-header {
            background:{{ \Illuminate\Support\Facades\Config::get('sitec.facilitador.primary_color','#22A7F0') }};
            border-color:{{ \Illuminate\Support\Facades\Config::get('sitec.facilitador.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ \Illuminate\Support\Facades\Config::get('sitec.facilitador.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ \Illuminate\Support\Facades\Config::get('sitec.facilitador.primary_color','#22A7F0') }};
        }
        .facilitador .breadcrumb a{
            color:{{ \Illuminate\Support\Facades\Config::get('sitec.facilitador.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(\Illuminate\Support\Facades\Config::get('sitec.facilitador.additional_css')))<!-- Additional CSS -->
        @foreach(\Illuminate\Support\Facades\Config::get('sitec.facilitador.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>

<body class="facilitador @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

<div id="facilitador-loader">
    <?php $admin_loader_img = Facilitador::setting('admin.loader', ''); ?>
    @if($admin_loader_img == '')
        <img src="{{ facilitador_asset('images/logo-icon.png') }}" alt="Facilitador Loader">
    @else
        <img src="{{ Facilitador::image($admin_loader_img) }}" alt="Facilitador Loader">
    @endif
</div>

<?php
if (!is_object(Auth::user())) {
    $user_avatar = '';
} else if (\Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http://') || \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'https://')) {
    $user_avatar = Auth::user()->avatar;
} else {
    $user_avatar = Facilitador::image(Auth::user()->avatar);
}
?>

<div class="app-container">
    <div class="fadetoblack visible-xs"></div>
    <div class="row content-container">
        @include('support::layouts.voyager.dashboard.navbar')
        @include('support::layouts.voyager.dashboard.sidebar')
        <script>
            (function(){
                    var appContainer = document.querySelector('.app-container'),
                        sidebar = appContainer.querySelector('.side-menu'),
                        navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                        loader = document.getElementById('facilitador-loader'),
                        hamburgerMenu = document.querySelector('.hamburger'),
                        sidebarTransition = sidebar.style.transition,
                        navbarTransition = navbar.style.transition,
                        containerTransition = appContainer.style.transition;

                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                    if (window.innerWidth > 768 && window.localStorage && window.localStorage['facilitador.stickySidebar'] == 'true') {
                        appContainer.className += ' expanded no-animation';
                        loader.style.left = (sidebar.clientWidth/2)+'px';
                        hamburgerMenu.className += ' is-active no-animation';
                    }

                   navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                   sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                   appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
            })();
        </script>
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body padding-top">
                @yield('page_header')
                <div id="facilitador-notifications"></div>
                <?php if(isset($content)) echo $content; ?>
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('support::partials.app-footer')

<!-- Javascript Libs -->


<script type="text/javascript" src="{{ facilitador_asset('js/app.js') }}"></script>

<script>
    @if(Session::has('alerts'))
        let alerts = {!! json_encode(Session::get('alerts')) !!};
        helpers.displayAlerts(alerts, toastr);
    @endif

    @if(Session::has('message'))

    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
        alerter(alertMessage);
    } else {
        toastr.error("toastr alert-type " + alertType + " is unknown");
    }
    @endif
</script>
@include('stalker::media.manager')
@yield('javascript')
@stack('javascript')
@if(!empty(\Illuminate\Support\Facades\Config::get('sitec.facilitador.additional_js')))<!-- Additional Javascript -->
    @foreach(\Illuminate\Support\Facades\Config::get('sitec.facilitador.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
@endif

</body>
</html>
