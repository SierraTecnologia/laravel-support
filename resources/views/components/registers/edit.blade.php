@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {!! trans('words.cobertura') !!}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> {!! trans('words.home') !!}</a></li>
            <li><a href="{!! route('facilitador.index', [ $service->getModelService()->getCryptName() ]) !!}"><i class="fa fa-key"></i> {!! $service->getModelService()->getName(true) !!}</a></li>
            <li class="active">{!! trans('words.edit') !!}</li>
        </ol>
   </section>
   <div class="content">

       <div class="box card box-primary">
           <div class="box-body card-body">
               <div class="row">

                   @include('layouts.partials.message')

                   {!! Form::model($service->getModelService()->getModelClass(), ['url' => route('facilitador.index', [ $service->getModelService()->getCryptName(), $service->getCryptName()]), 'method' => 'patch']) !!}

                        @include('support::components.registers.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection