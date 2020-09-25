@foreach ($modelRelationsResults as $relationResult)

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box panel card box-solid">
                <div class="box-header panel-header card-header with-border">
                    <i class="fa fa-text-width"></i>

                    <h3 class="box-title panel-title card-title">
                        {!! $service->getModelService()->getName() !!} {!! $relationResult->repository->getModelService()->getName(true) !!}
                    </h3>
                </div>
                <!-- /.box-header panel-header card-header -->
                <div class="btn-group">
                    <h1 class="float-right">
                        <a class="btn btn-primary float-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('rica.create', [$service->getModelService()->getCryptName()]) !!}">{!! trans('words.addNew') !!}</a>
                    </h1>
                </div>
                <div class="box-body panel-body card-body">
                        @include(
                            'support::components.repositories.table',
                            [
                                'registros' => $relationResult->results,
                                'service' => $relationResult->repository
                            ]
                        )
                </div>
                <!-- /.box-body panel-body card-body -->
            </div>
        </div>
    </div>


@endforeach


