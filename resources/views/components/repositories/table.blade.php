<?php /**<table class="table table-responsive" id="coberturas-table">
    <thead>
        @foreach ($service->getDiscoverService()->getColumns() as $eloquentColumn)
            <th>{!! $eloquentColumn->getName() !!}</th>
        @endforeach
        <th colspan="3">{!! trans('words.action') !!}</th>
    </thead>
    <tbody>
        @if (!empty($registros))
            @foreach($registros as $cobertura)
                <tr>
                                        @foreach($dataType->browseRows as $row)
                    @foreach ($service->getDiscoverService()->browseRows as $row)
                        <td>{!! $row->displayFromModel($cobertura) !!}</td>
                    @endforeach
                    <td>
                        {!! Form::open(['route' => ['rica.destroy', $service->getModelService()->getCryptName(), Crypto::shareableEncrypt($cobertura->{$service->getDiscoverService()->getPrimaryKey()})], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{!! route('rica.show', [ $service->getModelService()->getCryptName(), Crypto::shareableEncrypt($cobertura->{$service->getDiscoverService()->getPrimaryKey()})]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                            <a href="{!! route('rica.edit', [ $service->getModelService()->getCryptName(), Crypto::shareableEncrypt($cobertura->{$service->getDiscoverService()->getPrimaryKey()})]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('".trans('phrases.areYouSure')."')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table> */?>
<?php
if (isset($registros) && !isset($showCheckboxColumn)) {
    if (!$fromCollection = \Support\Services\DatatableService::makeFromCollection($registros)) {

        // list(
        //     $actions,
        //     $dataType,
        //     $dataTypeContent,
        //     $isModelTranslatable,
        //     $search,
        //     $orderBy,
        //     $orderColumn,
        //     $sortOrder,
        //     $searchNames,
        //     $isServerSide,
        //     $defaultSearchKey,
        //     $usesSoftDeletes,
        //     $showSoftDeleted,
            $showCheckboxColumn = false;
        // ) = 
    } else {
        

        list(
            $actions,
            $dataType,
            $dataTypeContent,
            $isModelTranslatable,
            $search,
            $orderBy,
            $orderColumn,
            $sortOrder,
            $searchNames,
            $isServerSide,
            $defaultSearchKey,
            $usesSoftDeletes,
            $showSoftDeleted,
            $showCheckboxColumn,
        ) = $fromCollection->repositoryIndex();
    }
}
?>
@if ((!isset($dataTypeContent) || !$dataTypeContent || empty($dataTypeContent)) && (!isset($fromCollection) || !$fromCollection || empty($fromCollection)))
    Nenhum Registro p/ Mostrar

@else

    <table id="dataTable" class="table table-hover">
        <thead>
            <tr>
                @if($showCheckboxColumn)
                    <th>
                        <input type="checkbox" class="select_all">
                    </th>
                @endif
                @foreach($dataType->browseRows as $row)
                <th>
                    @if ($isServerSide)
                        <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                    @endif
                    {{ $row->getTranslatedAttribute('display_name') }}
                    @if ($isServerSide)
                        @if ($row->isCurrentSortField($orderBy))
                            @if ($sortOrder == 'asc')
                                <i class="facilitador-angle-up pull-right"></i>
                            @else
                                <i class="facilitador-angle-down pull-right"></i>
                            @endif
                        @endif
                        </a>
                    @endif
                </th>
                @endforeach
                <th class="actions text-right">{{ __('facilitador::generic.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataTypeContent as $data)
            <tr>
                @if($showCheckboxColumn)
                    <td>
                        <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                    </td>
                @endif
                @foreach($dataType->browseRows as $row)
                    @php
                    if ($data->{$row->field.'_browse'}) {
                        $data->{$row->field} = $data->{$row->field.'_browse'};
                    }
                    @endphp
                    <td>
                        @if(!in_array($row->type, ['date', 'timestamp']) && is_object($data->{$row->field}))
                            <div>{{ var_dump( $data->{$row->field}, true ) }}</div>
                        @elseif (isset($row->details->view))
                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details])
                        @elseif($row->type == 'image')
                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Facilitador::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                        @elseif($row->type == 'relationship')
                            @include('pedreiro::shared.forms.fields.relationship', ['view' => 'browse','options' => $row->details])
                        @elseif($row->type == 'select_multiple')
                            @if(property_exists($row->details, 'relationship'))

                                @foreach($data->{$row->field} as $item)
                                    {{ $item->{$row->field} }}
                                @endforeach

                            @elseif(property_exists($row->details, 'options'))
                                @if (!empty(json_decode($data->{$row->field})))
                                    @foreach(json_decode($data->{$row->field}) as $item)
                                        @if (@$row->details->options->{$item})
                                            {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('facilitador::generic.none') }}
                                @endif
                            @endif

                            @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                @if (@count(json_decode($data->{$row->field})) > 0)
                                    @foreach(json_decode($data->{$row->field}) as $item)
                                        @if (@$row->details->options->{$item})
                                            {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('facilitador::generic.none') }}
                                @endif

                        @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))

                            {!! $row->details->options->{$data->{$row->field}} ?? '' !!}

                        @elseif($row->type == 'date' || $row->type == 'timestamp')
                            @if ( !empty($row->details) && property_exists($row->details, 'format') && !is_null($data->{$row->field}) )
                                {{ \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) }}
                            @elseif ( !is_null($data->{$row->field}) && is_a($data->{$row->field}, \Carbon\Carbon::class) )
                                {{ \Carbon\Carbon::parse($data->{$row->field})->diffForHumans() }}
                            @else
                                {{ $data->{$row->field} }}
                            @endif
                        @elseif($row->type == 'checkbox')
                            @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                @if($data->{$row->field})
                                    <span class="label label-info">{{ $row->details->on }}</span>
                                @else
                                    <span class="label label-primary">{{ $row->details->off }}</span>
                                @endif
                            @else
                            {{ $data->{$row->field} }}
                            @endif
                        @elseif($row->type == 'color')
                            <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                        @elseif($row->type == 'text')
                            @if(is_array($data->{$row->field}))
                                <div>
                                {{ implode(', ', $data->{$row->field}) }}
                                </div>
                            @else
                                @include('pedreiro::multilingual.input-hidden-bread-browse')
                                <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                            @endif
                        @elseif($row->type == 'text_area')
                            @if(is_array($data->{$row->field}))
                                <div>
                                {{ implode(', ', $data->{$row->field}) }}
                                </div>
                            @else
                                @include('pedreiro::multilingual.input-hidden-bread-browse')
                                <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                            @endif
                        @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                            @include('pedreiro::multilingual.input-hidden-bread-browse')
                            @if(json_decode($data->{$row->field}) !== null)
                                @foreach(json_decode($data->{$row->field}) as $file)
                                    <a href="{{ Storage::disk(\Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                        {{ $file->original_name ?: '' }}
                                    </a>
                                    <br/>
                                @endforeach
                            @else
                                <a href="{{ Storage::disk(\Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                    Download
                                </a>
                            @endif
                        @elseif($row->type == 'rich_text_box')
                            @if(is_array($data->{$row->field}))
                                <div>
                                {{ implode(', ', $data->{$row->field}) }}
                                </div>
                            @else
                                @include('pedreiro::multilingual.input-hidden-bread-browse')
                                <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                            @endif
                        @elseif($row->type == 'coordinates')
                            @include('facilitador::partials.coordinates-static-image')
                        @elseif($row->type == 'multiple_images')
                            @php $images = json_decode($data->{$row->field}); @endphp
                            @if($images)
                                @php $images = array_slice($images, 0, 3); @endphp
                                @foreach($images as $image)
                                    <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Facilitador::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                @endforeach
                            @endif
                        @elseif($row->type == 'media_picker')
                            @php
                                if (is_array($data->{$row->field})) {
                                    $files = $data->{$row->field};
                                } else {
                                    $files = json_decode($data->{$row->field});
                                }
                            @endphp
                            @if ($files)
                                @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                    @foreach (array_slice($files, 0, 3) as $file)
                                    <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Facilitador::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                    @endforeach
                                @else
                                    <ul>
                                    @foreach (array_slice($files, 0, 3) as $file)
                                        <li>{{ $file }}</li>
                                    @endforeach
                                    </ul>
                                @endif
                                @if (count($files) > 3)
                                    {{ __('facilitador::media.files_more', ['count' => (count($files) - 3)]) }}
                                @endif
                            @elseif (is_array($files) && count($files) == 0)
                                {{ trans_choice('facilitador::tools.media.files', 0) }}
                            @elseif ($data->{$row->field} != '')
                                @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Facilitador::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                @else
                                    {{ $data->{$row->field} }}
                                @endif
                            @else
                                {{ trans_choice('facilitador::tools.media.files', 0) }}
                            @endif
                        @else
                            @include('pedreiro::multilingual.input-hidden-bread-browse')
                            <span>{{ $data->{$row->field} }}</span>
                        @endif
                    </td>
                @endforeach
                <td class="no-sort no-click" id="bread-actions">
                    @foreach($actions as $action)
                        @if (!method_exists($action, 'massAction'))
                            @include('support::cruds.bread.partials.actions', ['action' => $action])
                        @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif