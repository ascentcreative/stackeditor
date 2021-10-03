
@isset($content)

    @foreach(json_decode($content)->rows as $item)

        @if($item->published ?? true)

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
            
                <div class="row" xstyle="padding: 0px 0 20px">

                    @isset($item->blocks)

                        @php 
                            if($item->collapseorder == 'right-to-left') {
                                $blocks = array_reverse((array) $item->blocks);
                            } else {
                                $blocks = $item->blocks;
                            }
                        
                        @endphp

                        
        
                        @foreach($blocks as $block) 
                    
                            <div class="stack-block col-md-{{$block->cols->width}} xcol-sm-{{$block->cols->width * 2}} @if($block->cols->width < 6) hyphenbreak @endif">
                                
                                <div id="block-{{ $block->unid }}">
                    
                                    @includeFirst(['stackeditor.block.' . $block->type . '.show', 'cms::stackeditor.block.' . $block->type . '.show', 'stackeditor::block.' . $block->type . '.show'], ['data'=>$item])

                                </div>

                            </div>
                    
                        @endforeach
                    @endisset
                
                </div>

            
            {{-- //@if(!isset($item->fullwidth) || !$item->fullwidth)  --}}
                </div> 
            {{-- @endif --}}
    

        </div>

        @endif
       
    @endforeach
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