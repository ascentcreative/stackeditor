@extends("stackeditor::block.base.edit")

@section('block-content')

    <div class="block-text-edit">
        <x-cms-form-wysiwyg label="" name="{{ $name }}[content]" value="{!! isset($value->content) ? $value->content : ''  !!}" wrapper="none"/>
    </div>

@overwrite

@section('block-settings')
@overwrite

@section('block-actions')
@overwrite