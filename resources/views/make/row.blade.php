
<x-stackeditor-row name="{{ $name }}[rows][{{$key}}]" :value="$value" :defaults="resolveDescriptor($blockType)::getRowDefaults()">

    <x-stackeditor-block type="{{ $blockType }}" name="{{ $name }}[rows][{{$key}}][blocks][0]" :value="$value ?? (object)[]"/>

</x-stackeditor-row>

@stack('lib')
@stack('scripts')