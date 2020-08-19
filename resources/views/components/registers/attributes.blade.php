<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box card box-solid">
            <div class="box card box-solid">
                <div class="box-header card-header with-border">
                    <i class="fa fa-text-width"></i>

                    <h3 class="box-title card-title">Description</h3>
                </div>
                <!-- /.box-header card-header -->
                <div class="box-body card-body">
                    <dl>
                        @foreach ($service->getDiscoverService()->getColumns() as $eloquentColumn)
                            <dt>{!! $eloquentColumn->getName() !!}</dt>
                            <dd>{!! $eloquentColumn->displayFromModel($register) !!}</dd>
                        @endforeach
                    </dl>
                </div>
            <!-- /.box-body card-body -->
            </div>
        </div>
    </div>
</div>