@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="float-left">{!! $service->getModelService()->getName(true) !!}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> {!! trans('words.home') !!}</a></li>
            <li class="active">{!! $service->getModelService()->getName(true) !!}</li>
        </ol>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('layouts.partials.message')
        

        <div class="clearfix"></div>

        <div class="box panel card box-primary panel-primary card-primary">
            <div class="btn-group">
                <h1 class="float-right">
                    <a class="btn btn-primary float-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('rica.create') !!}">{!! trans('words.addNew') !!}</a>
                </h1>
            </div>
            <div class="box-body panel-body card-body">
                    @include('support::components.repositories.table')
            </div>
        </div>
    </div>
@endsection

