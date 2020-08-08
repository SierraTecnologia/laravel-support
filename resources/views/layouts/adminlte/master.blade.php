@extends('adminlte::page')

@push('css')
    @if (View::exists('socrates::botman.partials.css'))
        @include('socrates::botman.partials.css')
    @elseif (View::exists('boravel::botman.partials.css'))
        @include('boravel::botman.partials.css')
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css">
@endpush

@section('js')
    @parent
    @if (View::exists('socrates::botman.partials.js'))
        @include('socrates::botman.partials.js')
    @elseif (View::exists('boravel::botman.partials.js'))
        @include('boravel::botman.partials.js')
    @endif
    @stack('javascript')
    @yield('javascript')
@stop

@section('title')
    @parent
    <?php if(isset($title)) echo $title; ?>
    @stack('title')
    @yield('title')
@stop

@section('content_header')
    @parent
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php if(isset($title)) echo $title; ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            @section('breadcrumbs')
                <ol class="breadcrumb float-sm-right">
                    @php
                    if (!isset($segments))
                        $segments = array_filter(explode('/', str_replace(route('rica.dashboard'), '', Request::url())));
                    if (!isset($mainUrl))
                        $mainUrl = route('rica.dashboard');
                    @endphp
                    @if(count($segments) == 0)
                        <li class="breadcrumb-item active"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</li>
                    @else
                        <li class="breadcrumb-item active">
                            <a href="{{ route('rica.dashboard')}}"><i class="facilitador-boat"></i> {{ __('facilitador::generic.dashboard') }}</a>
                        </li>
                        @foreach ($segments as $segment)
                            @php
                            $mainUrl .= '/'.$segment;
                            @endphp
                            @if ($loop->last)
                                <li class="breadcrumb-item">{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $mainUrl }}">{{ \Support\Routing\UrlGenerator::displayStringName($segment) }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ol>
                @show
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
    @stack('content_header')
    @yield('content_header')
@stop

@section('content_top_nav_right')
<li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
    {!! \Translation::menuAdminLte() !!}
@stop

@section('content')
    @parent
    @if (isset($content))
      @if (is_string($content))
        {!! $content !!}
      @else
        {!! $content->render() !!}
      @endif
    @endif
    @stack('content')
    @yield('content')
@stop