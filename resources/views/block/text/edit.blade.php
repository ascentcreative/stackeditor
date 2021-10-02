@extends("stackeditor::block.base.edit")

@section('block-content')

    <div style="background: white; height: calc(100% + 40px); margin: -20px -40px -20px -20px;">
        <x-cms-form-wysiwyg label="" name="{{ $name }}[content]" value="{!! isset($value->content) ? $value->content : ''  !!}" wrapper="none"/>
    </div>

@overwrite