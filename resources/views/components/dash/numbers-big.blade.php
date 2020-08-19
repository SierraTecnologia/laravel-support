
@foreach ($models as $groupName=>$realModels)
    @php
    $i = 1
    @endphp
    <h2 class="page-header">{!! $groupName !!}</h2>
    <div class="row">
    @foreach ($realModels as $model)
        @if ($i==1)
            <div class="row">
        @endif
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                @if (is_array($model))
                    <span class="info-box-icon bg-aqua">{!! \Support\Template\Layout\Icons::withHtml($model['icon']) !!}</span>

                    <div class="info-box-content">
                    <span class="info-box-text"><a href="{{$model['url']}}">{{$model['name']}}</a></span>
                    <span class="info-box-number">{{$model['count']}}</span>
                    </div>
                @else
                    <span class="info-box-icon bg-aqua">{!! \Support\Template\Layout\Icons::withHtml($model->getIcon()) !!}</span>

                    <div class="info-box-content">
                    <span class="info-box-text"><a href="{{$model->getUrl()}}">{{$model->getName()}}</a></span>
                    <span class="info-box-number">{{$model->getRepository()->count()}}</span>
                    </div>
                @endif
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        @if ($i==4)
            </div>
            @php
            $i = 0
            @endphp
        @endif
        @php
        $i = $i+1
        @endphp
    @endforeach
    </div>
@endforeach
