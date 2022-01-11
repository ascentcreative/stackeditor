
@isset($content)

    @php 
    
        if(is_string($content)) {
            $content = json_decode($content); //, true);
        } else {
            // this feels like such a fudge but works... 
            // used on validation fail when the incoming data is a pure array. 
            // encode / decode makes it match the expected object style.
            // works for now, but feels uncontrolled....
            // dd(json_decode(json_encode($content));
            $content = json_decode(json_encode($content));
        }
    
      

        // dd($content);

        $rows = $content->rows;
    
    @endphp

    @isset($rows)

        @foreach($rows as $item)

            @if($item->published ?? true)

                @isset($item->blocks)

                    <div class="stack-row" id="row-{{ $item->unid }}" 

                            
                            @if(!Agent::isMobile())
                                @if(isset($item->bgimage) && $item->bgimage != '') 
                                    {{-- Parallax image for non-mobile users. --}}
                                    data-android-fix="false" class="parallax-window" data-parallax="scroll" data-image-src="/storage/{{ \AscentCreative\CMS\Models\File::find($item->bgimage)->filepath }}"
                                @endif
                            @endif         

                            >

                            {{-- To allow for blended BG colours over images, put the color as an overlaid div. --}}
                            @if(isset($item->bgcolor) && $item->bgcolor != '' && isset($item->bgimage) && $item->bgimage != '') 
                                <div class="bgcolor" style="background-color: {{ $item->bgcolor }}; position: absolute; top: 0; bottom: 0; left: 0; right: 0"></div>
                            @endif

                        {{-- @if(!isset($item->fullwidth) || !$item->fullwidth)  --}}
                            <div 
                                @if(!isset($item->contentwidth) || $item->contentwidth != '100%')
                                class="centralise" 
                                @endif
                                @if(isset($item->contentwidth) && $item->contentwidth != '')
                                style="max-width: {{ $item->contentwidth }}"
                                @endif
                                >
                        {{-- //@endif --}}
                        
                            <div class="grid-row xflex xrow">

                                    @php 
                                        if($item->collapseorder == 'right-to-left') {
                                            $blocks = array_reverse((array) $item->blocks);
                                        } else {
                                            $blocks = $item->blocks;
                                        }
                                    
                                    @endphp                                    
                    
                                    @foreach($blocks as $block) 
                                
                                        <div id="block-{{ $block->unid }}-outer" style="" class="stack-block {{--  col-md-{{$block->cols->width}} col-sm-{{$block->cols->width * 2}} --}} {{-- @if($block->cols->width < 6) zhyphenbreak @endif --}}">
                                            
                                            <div id="block-{{ $block->unid }}" style="height: 100%;">
                                
                                                @includeFirst(stackeditorBladePaths($block->type, 'show'), ['data'=>$item])

                                            </div>

                                        </div>
                                
                                    @endforeach
                            
                            
                            </div>

                        
                        {{-- //@if(!isset($item->fullwidth) || !$item->fullwidth)  --}}
                            </div> 
                        {{-- @endif --}}
                

                    </div>

                @endisset

            @endif
        
        @endforeach

    @endisset

@endisset

@push('styles')
        {{-- Load the CSS for this stack --}}
        @if(request()->isPreview)
            {{-- render the CSS directly to a style tag on the page --}}
            <style>
            {!! renderStackCSS($content) !!}
            </style>
        @else
            {{-- render the CSS to a file which can be minified for performance etc --}}
            @style(getStackCSSFile($content, $model))
        @endif

@endpush

@push('scripts')
    
<script>
    $(document).ready(function() {
        $('.match-height').matchHeight(true);
    });

    $(window).on('resize', function() {
        $('.match-height').matchHeight(true);
    }); 
</script>

@endpush