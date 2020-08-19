
<!-- Clients Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_types_id', trans('words.address_type').':') !!}
    {!! Form::select(
        'address_types_id', AddressService::typesForCoberturaForSelect(), 'S', ['class' => 'form-control']
    ) !!}
</div>

<!-- Cobertura Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cobertura_category_id', trans('words.category').':') !!}
    {!! Form::select(
        'cobertura_category_id', \App\Models\Category::pluck('name', 'id')->toArray(), 'S', ['class' => 'form-control']
    ) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cep', trans('words.cep').':') !!}
    {!! Form::text('cep', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(trans('words.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('facilitador.index') !!}" class="btn btn-default">{!! trans('words.cancel') !!}</a>
</div>
