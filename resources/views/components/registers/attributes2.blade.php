<div class="row">
        @foreach ($service->getDiscoverService()->getColumns() as $eloquentColumn)
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box card box-solid">
                    <div class="box-header card-header with-border">
                        <i class="fa fa-text-width"></i>
    
                        <h3 class="box-title card-title">{!! $eloquentColumn->getName() !!}</h3>
                    </div>
                    <!-- /.box-header card-header -->
                    <div class="box-body card-body">
                        <blockquote>
                            {!! $eloquentColumn->displayFromModel($register) !!}
                        </blockquote>
                    </div>
                    <!-- /.box-body card-body -->
                </div>
            </div>
        @endforeach
    </div>