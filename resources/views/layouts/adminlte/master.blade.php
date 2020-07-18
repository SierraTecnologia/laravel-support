@extends('adminlte::page')

@push('css')
    @include('boravel::botman.partials.css')
@endpush

@section('js')
    @parent
    @include('boravel::botman.partials.js')
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
                        $segments = array_filter(explode('/', str_replace(route('facilitador.dashboard'), '', Request::url())));
                    if (!isset($mainUrl))
                        $mainUrl = route('facilitador.dashboard');
                    @endphp
                    @if(count($segments) == 0)
                        <li class="breadcrumb-item active"><i class="facilitador-boat"></i> {{ __('support::generic.dashboard') }}</li>
                    @else
                        <li class="breadcrumb-item active">
                            <a href="{{ route('facilitador.dashboard')}}"><i class="facilitador-boat"></i> {{ __('support::generic.dashboard') }}</a>
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

@section('content')
    @parent
    <?php if(isset($content)) echo $content; ?>
    @stack('content')
    @yield('content')
@stop