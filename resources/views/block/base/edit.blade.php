@php 
    $blockid = "block-" . $value->unid;
@endphp

{{-- <div class="block" style="width: {{ (100 / 12) * ($value->cols->width ?? 12) }}%;"> --}}

    <div class="block" style="grid-column: {{ ($value->cols->lg->start ?? 1) }} / span  {{ $value->cols->lg->width ?? 12 }}">

    <div class="block-content" @if(!env('STACKEDITOR_DEBUG', false)) style="height: 100%;" @endif>

        <div class="block-actions flex flex-column" style="justify-content: space-between">
            <div>
                <div class="block-handle bi-arrows-move xbi-arrow-left-right"></div>
            </div>

            <div class="flex flex-column" style="flex-grow: 0">
                <a href="" class="xblock-settings bi-gear" data-toggle="modal" data-target="#{{ $blockid }}-settings"></a>
                <a href="" class="block-delete bi-trash"></a>
            </div>
        </div>
       
        
        <div class="block-data" style="padding: 10px; width: 100%; padding-right: 30px; height: 100%;">

            @section('block-content')
                BLOCK CONTENT - OVERRIDE ME
            @show
            
        </div>
       
    </div>

    <div class="block-cols" @if(!env('STACKEDITOR_DEBUG', false)) style="display: none" @endif>

        <DIV>
        LG: <input type="text" name="{{ $name }}[cols][lg][start]" class="block-col-lg-start" value="{{ $value->cols->lg->start ?? 1}}" /><input type="text" class="block-col-lg-count" name="{{ $name }}[cols][lg][width]" value="{{ $value->cols->lg->width ?? 12 }}" />
        </DIV>

        <div>
        MD: <input type="text" name="{{ $name }}[cols][md][start]" class="block-col-md-start" value="{{ $value->cols->md->start ?? 'auto'}}" /><input type="text" class="block-col-md-count" name="{{ $name }}[cols][md][width]" value="{{ $value->cols->md->width ?? 4 }}" />
        </div>

        <div>
        SM: <input type="text" name="{{ $name }}[cols][sm][start]" class="block-col-sm-start" value="{{ $value->cols->sm->start ?? 'auto'}}" /><input type="text" class="block-col-sm-count" name="{{ $name }}[cols][sm][width]" value="{{ $value->cols->sm->width ?? 2 }}" />
        </div>

        Type: <input type="text" class="block-type" name="{{ $name }}[type]" value="{{ $type }}" />

        <input type="hidden" class="block-unid" name="{{ $name }}[unid]" value="{{ $value->unid }}" />
    </div>


    {{-- Wrap settings in a modal --}}
    <x-cms-modal modalid="{{ $blockid }}-settings" title="Block Settings" :closebutton="false">

        <div class="container">
        @section('block-settings')
        
           
        @show

            <x-cms-form-colour label="Background Colour" name="{{ $name }}[bgcolor]" :value="$value->bgcolor ?? ($defaults['bgcolor'] ?? 'transparent')" elementClass="block-bgcolor"/>

            <div class="border p-2 mb-2">
                <div><strong>Padding</strong></div>
                <x-cms-form-input type="text" name="{{ $name }}[padding][top]" label="Top" :value="$value->padding->top ?? ($defaults['padding-top'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][bottom]" label="Bottom" :value="$value->padding->bottom ??  ($defaults['padding-bottom'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][left]" label="Left" :value="$value->padding->left ??  ($defaults['padding-left'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[padding][right]" label="Right" :value="$value->padding->right ??  ($defaults['padding-right'] ?? 0)"/>
            </div>

            <div class="border p-2">
                <div><strong>Margin</strong></div>
                <x-cms-form-input type="text" name="{{ $name }}[margin][top]" label="Top" :value="$value->margin->top ??  ($defaults['margin-top'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][bottom]" label="Bottom" :value="$value->margin->bottom ?? ($defaults['margin-bottom'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][left]" label="Left" :value="$value->margin->left ??  ($defaults['margin-left'] ?? 0)"/>
                <x-cms-form-input type="text" name="{{ $name }}[margin][right]" label="Right" :value="$value->margin->right ??  ($defaults['margin-right'] ?? 0)"/>
            </div>

        </div>

        <x-slot name="footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal"> OK </button>
        </x-slot>
        
    </x-cms-modal>
    {{-- End modal --}}


</div>