@extends('layouts.app')

@section('pageTitle') Widgets @stop

@section('content')
<!-- 
    <div class="col-md-12 mt-2">
        include('features.widgets.breadcrumbs', ['location' => ['create']])
    </div> -->

    <div class="row">
        <div class="col-md-12">
            <div class="box card box-primary">
                <div class="box-header card-header with-border">
                    <h3 class="box-title card-title">{!! $service->getModelService()->getName() !!}</h3>
                </div>
                <div class="box-body card-body">
                    {!! Form::open(['url' => route('facilitador.store', [ $service->getModelService()->getCryptName()]), 'class' => 'add']) !!}

                        {!! FormMaker::fromTable($service->getModelService()->getTableName(), $service->getModelService()->getFieldForForm()) !!}

                        <div class="form-group text-right">
                            <a href="{!! $service->getModelService()->getUrl() !!}" class="btn btn-secondary raw-left">Cancel</a>
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
