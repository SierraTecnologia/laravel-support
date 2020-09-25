<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box panel card box-solid">
            <div class="box panel card box-solid">
                <div class="box-header panel-header card-header with-border">
                    <i class="fa fa-text-width"></i>

                    <h3 class="box-title panel-title card-title">Description</h3>
                </div>
                <!-- /.box-header panel-header card-header -->
                <div class="box-body panel-body card-body">
                    <dl>
                        @foreach ($service->getDiscoverService()->getColumns() as $eloquentColumn)
                            <dt>{!! $eloquentColumn->getName() !!}</dt>
                            <dd>{!! $eloquentColumn->displayFromModel($register) !!}</dd>
                        @endforeach
                    </dl>
                </div>
            <!-- /.box-body panel-body card-body -->
            </div>
        </div>
    </div>
</div>