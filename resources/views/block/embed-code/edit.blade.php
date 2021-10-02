@extends("stackeditor::block.base.edit")



@section('block-content')


<div class="flex" style="flex: 0 0 200px">
    <H2 class="text-muted text-center" style="width: 100%;">EMBED CODE</H2>
</div>

<div class="container">
    <x-cms-form-code label="Code" name="{{ $name }}[code]" :value="$value->code ?? ''" wrapper="none"/>
</div>


@overwrite

@section('block-settings')
@overwrite

@section('block-actions')
@overwrite