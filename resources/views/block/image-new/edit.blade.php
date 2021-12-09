@extends("stackeditor::block.base.edit")

@section('block-content')
    {{-- <x-cms-form-croppie width="800" label="" name="{{ $name }}[image]" value="{!! isset($value->image) ? $value->image : ''  !!}" wrapper="none"/> --}}
        New image...
@overwrite