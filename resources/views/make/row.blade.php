<x-stackeditor-row name="{{ $name }}[{{$key}}]" :value="$value">

    <x-stackeditor-block type="{{ $blockType }}" name="{{ $name }}[{{$key}}][blocks][0]" :value="$value"/>

</x-stackeditor-row>

@stack('lib')
@stack('scripts')