#{{ $id }} {

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

}

#{{ $id }}-outer {

    {{-- height: 100%; --}}

    {{-- MARGINS --}}
    @if($data->margin->top && $data->margin->top != '') 
        padding-top: {{ $data->margin->top }};
    @endif 

    @if($data->margin->bottom && $data->margin->bottom != '') 
        padding-bottom: {{ $data->margin->bottom }};
    @endif 

    @if($data->margin->left && $data->margin->left != '') 
        padding-left: {{ $data->margin->left }};
    @endif 

    @if($data->margin->right && $data->margin->right != '') 
        padding-right: {{ $data->margin->right }};
    @endif 

    {{-- grid-column: span {{ $data->cols->width }}; --}}
    grid-column: {{ $data->cols->lg->start }} / span {{ $data->cols->lg->width }};

}

@media only screen and (max-width: 905px) {

    #{{ $id }}-outer {
        grid-column:  {{ $data->cols->md->start }} / span {{ $data->cols->md->width }};
    }

}

@media only screen and (max-width: 500px) {

    #{{ $id }}-outer { 
        grid-column:  {{ $data->cols->sm->start }} / span {{ $data->cols->sm->width }};
    }

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

