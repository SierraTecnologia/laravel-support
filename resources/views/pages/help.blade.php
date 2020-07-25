@extends('layouts.app')

@section('content')
    <div class="row">
        <h1 class="page-header text-center">{!! trans('words.help') !!}</h1>
    </div>
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">bla bla bla  ?</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
            bla bla bla
        </div>
        <!-- /.box-body -->
    </div>

@stop
