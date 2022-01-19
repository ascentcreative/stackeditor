@section('startlink')
    @if($block->linkurl ?? '')
        <A href="{{ $block->linkurl }}">
    @endif
@overwrite

@section('endlink') 
    @if($block->linkurl ?? '') </A> @endif
@overwrite


@php 
    switch($block->clipmask ?? '') {

        case 'none':
            $clip = '';
            break;

        case 'circle':
            $clip = "clip-path: circle();";
            break;

        default:
            $clip = "clip-path: url(#" . $block->clipmask . ");";
            break;
    }
@endphp 



@if( ($block->objectfit ?? 'fitwidth') == 'fitwidth')
   
    @yield('startlink')
        {{-- <IMG src="{{ $block->image }}" width="100%" height="auto" alt="{{ $block->alttext ?? '' }}"/> --}}
            <x-cms-multisizeimage src="{{ $block->image }}" width="100%" height="auto" alt="{{ $block->alttext ?? '' }}" :style="$clip"/>
    @yield('endlink')

@else   

    <div class="image-wrap" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;">
        @yield('startlink')
            <x-cms-multisizeimage src="{{ $block->image }}" width="100%" height="100%" style="{{ $clip }} object-fit: {{ $block->objectfit }}; object-position: center" alt="{{ $block->alttext ?? '' }}"/>
        @yield('endlink')   
    </div>

@endif
