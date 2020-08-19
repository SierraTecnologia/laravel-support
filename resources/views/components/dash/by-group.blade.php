<div class="col-md-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach ($models as $groupName=>$realModels)
            <li class="{{ $loop->first ? 'active' : '' }}"><a href="#tab_{!! $groupName !!}" data-toggle="tab">{!! $groupName !!}</a></li>
        @endforeach
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Dropdown <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
            <li role="presentation" class="divider"></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
        </ul>
        </li>
        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
    </ul>
    <div class="tab-content">
        @foreach ($models as $groupName=>$realModels)
        <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="tab_{!! $groupName !!}">



            @include('support::components.dash.by-subgroup', [
                'models' => $realModels->groupBy('history_type'),
                'identificador' => $groupName
            ])


        </div>
        <!-- /.tab-pane -->
        @endforeach
    </div>
    <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
</div>