@extends('layouts.app')

@section('content')
    <div class="row">
        <h1 class="page-header text-center">{!! trans('words.changelog') !!}</h1>
    </div>
    @foreach($releases as $release)
        <div class="box">
            <div class="box-header with-border">
            <h3 class="box-title">{!! $release->getName() !!}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">

                    @if (!empty($changes = $release->getChanges('Added')))
                        <dt><p class="text-green">Added:</p></dt>
                        @foreach($changes as $change)
                        <dd><p class="text-green">{!! $change !!}</p></dd>
                        @endforeach
                    @endif
                    @if (!empty($changes = $release->getChanges('Changed')))
                    <dt><p class="text-yellow">Changed:</p></dt>
                        @foreach($changes as $change)
                        <dd><p class="text-yellow">{!! $change !!}</p></dd>
                        @endforeach
                    @endif
                    @if (!empty($changes = $release->getChanges('Removed')))
                    <dt><p class="text-aqua">Removed:</p></dt>
                        @foreach($changes as $change)
                        <dd><p class="text-aqua">{!! $change !!}</p></dd>
                        @endforeach
                    @endif
                    @if (!empty($changes = $release->getChanges('Fixed')))
                    <dt><p class="text-red">Fixed:</p></dt>
                        @foreach($changes as $change)
                        <dd><p class="text-red">{!! $change !!}</p></dd>
                        @endforeach
                    @endif
                </dl>
            </div>
            <!-- /.box-body -->
        </div>
    @endforeach

@stop
