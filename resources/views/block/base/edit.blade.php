@php 
    $blockid = "block-" . uniqid();
@endphp

<div class="block" style="width: {{ (100 / 12) * ($value->cols->width ?? 12) }}%;">

    <div class="block-content" style="height: 100%;">

        <div class="block-handle bi-arrows-move xbi-arrow-left-right"></div>
        <a href="" class="block-settings bi-gear" data-toggle="modal" data-target="#{{ $blockid }}-settings"></a>
        <a href="" class="block-delete bi-trash"></a>
        
        <div class="block-data" style="padding: 10px; width: 100%; padding-right: 30px; height: 100%;">
            @section('block-content')
                BLOCK CONTENT - OVERRIDE ME
            @show
        </div>
        
        <div style="display: none">
            Cols = Start: <input type="text" name="{{ $name }}[cols][start]" class="block-col-start" value="{{ $value->cols->start ?? 1}}" /> 
            Width: <input type="text" class="block-col-count" name="{{ $name }}[cols][width]" value="{{ $value->cols->width ?? 12 }}" />
            Type: <input type="text" class="block-type" name="{{ $name }}[type]" value="{{ $type }}" />

            <input type="hidden" class="block-unid" name="{{ $name }}[unid]" value="{{ $value->unid ?? uniqid() }}" />
        </div>
        
       
    </div>


    {{-- Wrap settings in a modal --}}
    <x-cms-modal modalid="{{ $blockid }}-settings" title="Block Settings" :closebutton="false">

        <div class="container">
        @section('block-settings')
        
           
        @show


            <x-cms-form-colour label="Background Colour" name="{{ $name }}[bgcolor]" :value="$value->bgcolor ?? 'transparent'" />

            <div class="border p-2 mb-2">
                <div><strong>Padding</strong></div>
                <x-cms-form-input type="text" name="{{ $name }}[padding][top]" label="Top" :value="$value->padding->top ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][bottom]" label="Bottom" :value="$value->padding->bottom ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][left]" label="Left" :value="$value->padding->left ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][right]" label="Right" :value="$value->padding->right ?? 0"/>
            </div>

            <div class="border p-2">
                <div><strong>Margin</strong></div>
                <x-cms-form-input type="text" name="{{ $name }}[margin][top]" label="Top" :value="$value->margin->top ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][bottom]" label="Bottom" :value="$value->margin->bottom ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][left]" label="Left" :value="$value->margin->left ?? 0"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][right]" label="Right" :value="$value->margin->right ?? 0"/>
            </div>
    



        </div>

        <x-slot name="footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal"> OK </button>
        </x-slot>
        
    </x-cms-modal>
    {{-- End modal --}}


</div>