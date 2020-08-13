@extends('layouts.app')

@section('content')
    @foreach($releases as $release)
        <div class="box card">
            <div class="box-header card-header with-border">
            <h3 class="box-title card-title">{!! $release->getName() !!}</h3>

            <div class="box-tools card-tools pull-right">
                {!! ($release->getDate()?(new Carbon\Carbon($release->getDate()))->diffForHumans():'') !!}
                <a href="{!! $release->getLink() !!}" class="btn btn-box-tool btn btn-tool">
                    View changes
                </a>
            </div>
            </div>
            <div class="box-body card-body">
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
            <!-- /.box-body card-body -->
        </div>
    @endforeach

@stop
