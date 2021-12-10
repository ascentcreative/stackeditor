@extends("stackeditor::block.base.edit")

@section('block-content')
    <x-cms-form-croppie xwidth="800" label="" name="{{ $name }}[image]" value="{!! isset($value->image) ? $value->image : ''  !!}" wrapper="none"/>
@overwrite

@section('block-settings')

    <x-cms-form-input type="text" label="Link to URL" name="{{ $name }}[linkurl]" :value="$value->linkurl ?? ''" />
    <x-cms-form-input type="text" label="Alt Text" name="{{ $name }}[alttext]" :value="$value->alttext ?? ''" />

    <x-cms-form-options type="select" name="{{ $name }}[objectfit]" label="Fit to block" :value="$value->objectfit ?? 'fitwidth'"
            :options="[
                'fitwidth'=>'Fit to block width',
                'contain'=>'Show the Full Image (may leave gaps)',
                'cover'=>'Fill the block (may crop the image)', 
            ]"
            >
        </x-cms-form-options>


@overwrite          


@section('block-actions')
@overwrite