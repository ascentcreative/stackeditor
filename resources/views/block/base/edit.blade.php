<div class="block" style="width: {{ (100 / 12) * ($value->cols->width ?? 12) }}%;">

    <div class="block-content" style="height: 100%;">

        <div class="block-handle bi-arrows-move xbi-arrow-left-right"></div>
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
        </div>
        
       
    </div>

    

</div>