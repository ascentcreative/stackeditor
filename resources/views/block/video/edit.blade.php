@extends("stackeditor::block.base.edit")

@section('block-content')
<div class="text-center pt-2">
    <x-cms-form-videoembed label="" name="{{ $name }}[video]" value="{!! isset($value->video) ? $value->video : '' !!} " wrapper="none"></x-cms-form-videoembed>
    {{-- Video Not Yet Implemented... --}}
    {{-- <button class="button btn-sm btn btn-primary text-small">Change</button> <button class="button btn-sm btn btn-primary text-small">Options</button> --}}
</div>
@overwrite


@section('block-settings')
@overwrite

@section('block-actions')
@overwrite