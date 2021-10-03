<x-stackeditor-row name="{{ $name }}[rows][{{$key}}]" :value="$value">

    <x-stackeditor-block type="{{ $blockType }}" name="{{ $name }}[rows][{{$key}}][blocks][0]" :value="$value"/>

</x-stackeditor-row>

@stack('lib')
@stack('scripts')