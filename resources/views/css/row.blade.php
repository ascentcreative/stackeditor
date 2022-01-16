#{{ $id }} {

    overflow-x: clip;

    {{-- BACKGROUND --}}
    @if($data->bgcolor && $data->bgcolor != '')
        background-color: {{ $data->bgcolor }};
    @endif

    {{-- @if(isset($data->bgimage) && $data->bgimage != '') 
        background-image: url('/storage/{{ \AscentCreative\CMS\Models\File::find($data->bgimage)->filepath }}');
        background-position: center;
        background-size: cover;
    @endif --}}


    {{-- PADDING --}}
    @if($data->padding->top && $data->padding->top != '') 
        padding-top: {{ $data->padding->top }};
    @endif 

    @if($data->padding->bottom && $data->padding->bottom != '') 
        padding-bottom: {{ $data->padding->bottom }};
    @endif 

    @if($data->padding->left && $data->padding->left != '') 
        padding-left: {{ $data->padding->left }};
    @endif 

    @if($data->padding->right && $data->padding->right != '') 
        padding-right: {{ $data->padding->right }};
    @endif 


    {{-- MARGINS --}}
    @if($data->margin->top && $data->margin->top != '') 
        margin-top: {{ $data->margin->top }};
    @endif 

    @if($data->margin->bottom && $data->margin->bottom != '') 
        margin-bottom: {{ $data->margin->bottom }};
    @endif 

    @if($data->margin->left && $data->margin->left != '') 
        margin-left: {{ $data->margin->left }};
    @endif 

    @if($data->margin->right && $data->margin->right != '') 
        margin-right: {{ $data->margin->right }};
    @endif 

}

#{{ $id }} .row {
    {{-- ALIGNMENT --}}
    @if(isset($data->alignitems)) 
        align-items: {{ $data->alignitems }};
    @endif

    @if(isset($data->collapseorder) && $data->collapseorder == 'right-to-left')
        flex-direction: row-reverse;
    @endif

}

