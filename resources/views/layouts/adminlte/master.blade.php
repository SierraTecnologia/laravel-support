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

@section('content')
    @parent
    <?php if(isset($content)) echo $content; ?>
    @stack('content')
    @yield('content')
@stop