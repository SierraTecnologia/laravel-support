@extends('facilitador::layouts.voyager.master')

@section('page_title', __('facilitador::generic.viewing').' '.__('facilitador::generic.database'))

@section('page_header')
    <h1 class="page-title">
        <i class="facilitador-data"></i> {{ __('facilitador::generic.database') }}
        <a href="{{ route('facilitador.database.create') }}" class="btn btn-success"><i class="facilitador-plus"></i>
            {{ __('facilitador::database.create_new_table') }}</a>
    </h1>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('facilitador::alerts')
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped database-tables">
                    <thead>
                        <tr>
                            <th>{{ __('facilitador::database.table_name') }}</th>
                            <th style="text-align:right" colspan="2">{{ __('facilitador::database.table_actions') }}</th>
                        </tr>
                    </thead>

                @foreach($tables as $table)
                    @continue(in_array($table->name, \Illuminate\Support\Facades\Config::get('sitec.facilitador.database.tables.hidden', [])))
                    <tr>
                        <td>
                            <p class="name">
                                <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug('database', 'show', $table->prefix.$table->name) }}"
                                   data-name="{{ $table->prefix.$table->name }}" class="desctable">
                                   {{ $table->name }}
                                </a>
                            </p>
                        </td>

                        <td>
                            <div class="bread_actions">
                            @if($table->dataTypeId)
                                <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug($table->slug, 'index') }}"
                                   class="btn-sm btn-warning browse_bread">
                                    <i class="facilitador-plus"></i> {{ __('facilitador::database.browse_bread') }}
                                </a>
                                <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug('bread', 'edit', $table->name) }}"
                                   class="btn-sm btn-default edit">
                                   {{ __('facilitador::cruds.bread.edit_bread') }}
                                </a>
                                <a data-id="{{ $table->dataTypeId }}" data-name="{{ $table->name }}"
                                     class="btn-sm btn-danger delete">
                                     {{ __('facilitador::cruds.bread.delete_bread') }}
                                </a>
                            @else
                                <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug('bread', 'create', $table->name) }}"
                                   class="btn-sm btn-default">
                                    <i class="facilitador-plus"></i> {{ __('facilitador::cruds.bread.add_bread') }}
                                </a>
                            @endif
                            </div>
                        </td>

                        <td class="actions">
                            <a class="btn btn-danger btn-sm pull-right delete_table @if($table->dataTypeId) remove-bread-warning @endif"
                               data-table="{{ $table->prefix.$table->name }}">
                               <i class="facilitador-trash"></i> {{ __('facilitador::generic.delete') }}
                            </a>
                            <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug('database', 'edit', $table->prefix.$table->name) }}"
                               class="btn btn-sm btn-primary pull-right" style="display:inline; margin-right:10px;">
                               <i class="facilitador-edit"></i> {{ __('facilitador::generic.edit') }}
                            </a>
                            <a href="{{ \Facilitador\Routing\UrlGenerator::routeForSlug('database', 'show', $table->prefix.$table->name) }}"
                               data-name="{{ $table->name }}"
                               class="btn btn-sm btn-warning pull-right desctable" style="display:inline; margin-right:10px;">
                               <i class="facilitador-eye"></i> {{ __('facilitador::generic.view') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_bread_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('facilitador::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="facilitador-trash"></i>  {!! __('facilitador::cruds.bread.delete_bread_quest', ['table' => '<span id="delete_bread_name"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_bread_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('facilitador::cruds.bread.delete_bread_conf') }}">
                    </form>
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('facilitador::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('facilitador::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="facilitador-trash"></i> {!! __('facilitador::database.delete_table_question', ['table' => '<span id="delete_table_name"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_table_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger pull-right" value="{{ __('facilitador::database.delete_table_confirm') }}">
                        <button type="button" class="btn btn-outline pull-right" style="margin-right:10px;"
                                data-dismiss="modal">{{ __('facilitador::generic.cancel') }}
                        </button>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('facilitador::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="facilitador-data"></i> @{{ table.name }}</h4>
                </div>
                <div class="modal-body" style="overflow:scroll">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('facilitador::database.field') }}</th>
                            <th>{{ __('facilitador::database.type') }}</th>
                            <th>{{ __('facilitador::database.null') }}</th>
                            <th>{{ __('facilitador::database.key') }}</th>
                            <th>{{ __('facilitador::database.default') }}</th>
                            <th>{{ __('facilitador::database.extra') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in table.rows">
                            <td><strong>@{{ row.Field }}</strong></td>
                            <td>@{{ row.Type }}</td>
                            <td>@{{ row.Null }}</td>
                            <td>@{{ row.Key }}</td>
                            <td>@{{ row.Default }}</td>
                            <td>@{{ row.Extra }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('facilitador::generic.close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('javascript')

    <script>

        var table = {
            name: '',
            rows: []
        };

        new Vue({
            el: '#table_info',
            data: {
                table: table,
            },
        });

        $(function () {

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault();
                href = $(this).attr('href');
                table.name = $(this).data('name');
                table.rows = [];
                $.get(href, function (data) {
                    $.each(data, function (key, val) {
                        table.rows.push({
                            Field: val.field,
                            Type: val.type,
                            Null: val.null,
                            Key: val.key,
                            Default: val.default,
                            Extra: val.extra
                        });
                        $('#table_info').modal('show');
                    });
                });
            });

            // Setup Delete Table
            //
            $('td.actions').on('click', '.delete_table', function (e) {
                table = $(this).data('table');
                if ($(this).hasClass('remove-bread-warning')) {
                    toastr.warning('{{ __('facilitador::database.delete_bread_before_table') }}');
                } else {
                    $('#delete_table_name').text(table);

                    $('#delete_table_form')[0].action = '{{ \Facilitador\Routing\UrlGenerator::routeForSlug('database', 'destroy', ['database' => '__database']) }}'.replace('__database', table)
                    $('#delete_modal').modal('show');
                }
            });

            // Setup Delete BREAD
            //
            $('table .bread_actions').on('click', '.delete', function (e) {
                id = $(this).data('id');
                name = $(this).data('name');

                $('#delete_bread_name').text(name);
                $('#delete_bread_form')[0].action = '{{ \Facilitador\Routing\UrlGenerator::routeForSlug('bread', 'delete', '__id') }}'.replace('__id', id);
                $('#delete_bread_modal').modal('show');
            });
        });
    </script>

@stop
