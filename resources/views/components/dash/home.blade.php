@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="float-left">

                <h2> Dashboard</h2>

                
            </div>

            <div class="float-right">

                {!! $htmlGenerator->optionsButtons() !!}
                
            </div>

        </div>

    </div>

    @include('support::components.dash.topbar', [
    
    ])

    @include('support::components.dash.numbers', [
        'models' => $models,
    ])

    <example-component></example-component>
@stop