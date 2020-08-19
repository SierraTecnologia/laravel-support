<div class="box-group" id="accordion">
    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

    @foreach ($models as $groupName=>$realModels)
        <div class="panel box box-{{ $loop->first ? 'primary' : 'danger' }}">
            <div class="box-header card-header with-border">
                <h4 class="box-title card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{!! $groupName !!}">
                    {!! $groupName !!}
                    </a>
                </h4>
            </div>
            <div id="collapse{!! $identificador !!}{!! $groupName !!}" class="panel-collapse collapse{{ $loop->first ? ' in' : '' }}">
                <div class="box-body card-body">

                    @include('support::components.dash.numbers', [
                        'models' => $realModels->groupBy('register_type'),
                        'identificador' => $identificador.$groupName
                    ])

                </div>
            </div>

        </div>
        <!-- /.tab-pane -->
    @endforeach
</div>