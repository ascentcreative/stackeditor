{{-- {!! $content !!} --}}

@isset($content)

    @if(request()->isPreview)
    <H1>PREVIEW</H1>
    @else 
    <H1>NOT PREVIEW</H1>
    @endif

{{-- @dd(json_decode($content)); --}}

    @foreach(json_decode($content)->rows as $item)

        @if($item->published ?? true)

        {{-- @dd($item) --}}

       
            <div 

            {{-- This needs to use some form of auto conversion... ie put everythig in a styles property to automatically write to the tag  --}}
           {{--  will also need to know how to format values (although these could be selected on the setting themselves) --}}
            style="@if(isset($item->bgcolor) && $item->bgcolor != '' && (!isset($item->bgimage) || $item->bgimage == '')) 
                    background-color: {{ $item->bgcolor }};
                @endif  @if(isset($item->padding->top) && $item->padding->top != 0) 
                    padding-top: {{ $item->padding->top }}px;
                @endif  @if(isset($item->padding->bottom) && $item->padding->bottom != 0) 
                    padding-bottom: {{ $item->padding->bottom }}px;
                @endif @if(isset($item->padding->left) && $item->padding->left != 0) 
                    padding-left: {{ $item->padding->left }}px;
                @endif @if(isset($item->padding->right) && $item->padding->right != 0) 
                    padding-right: {{ $item->padding->right }}px;
                @endif @if(isset($item->margin->top) && $item->margin->top != 0) 
                    margin-top: {{ $item->margin->top }}px;
                @endif @if(isset($item->margin->bottom) && $item->margin->bottom != 0) 
                    margin-bottom: {{ $item->margin->bottom }}px;
                @endif @if(isset($item->margin->left) && $item->margin->left != 0) 
                    margin-left: {{ $item->margin->left }}px;
                @endif @if(isset($item->margin->right) && $item->margin->right != 0) 
                    margin-right: {{ $item->margin->right }}px;
                @endif @if(Agent::isMobile() && isset($item->bgimage) && $item->bgimage != '')
                    {{-- Can't use parallax on mobile, so add the image as a normal backrgound --}}
                    background-image: url('/storage/{{ \AscentCreative\CMS\Models\File::find($item->bgimage)->filepath }}');
                    background-size: cover;
                    background-position: center;
                @endif" 
                
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

        
                <div class="row" style="padding: 0px 0 20px">

                    @isset($item->blocks)
                        @foreach($item->blocks as $block) 
                    
                            <div class="col-md-{{$block->cols->width}} xcol-sm-{{$block->cols->width * 2}} @if($block->cols->width < 6) hyphenbreak @endif">
                                
                                <div style="@if(isset($block->margin->top) && $block->margin->top != 0) 
                                            margin-top: {{ $block->margin->top }}px;
                                        @endif @if(isset($block->bgcolor) && $block->bgcolor != '') 
                                            background-color: {{ $block->bgcolor }};
                                        @endif ">
                    
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