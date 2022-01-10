{{-- @dd(request()->all()) --}}

<x-stackeditor-block type="{{ $blockType }}" name="{{ $name }}" :value="(object)['cols'=>(object)['width'=>$cols]]">

</x-stackeditor-block>

@stack('lib')
@stack('scripts')
@stack('styles')
