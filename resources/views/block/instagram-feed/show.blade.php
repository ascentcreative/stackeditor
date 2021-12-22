@php

    if(!$block->account) {
        return;
    }
    
    $profile = \Dymantic\InstagramFeed\Profile::find($block->account); //where('username', 'ascent_creative')->first();

    $igdata = $profile->feed(); //$block->imagecount ?? null);
 
    // @dump($igdata); 
    
@endphp

<div class="ig-grid">



    @for($iPost = 0; $iPost < $block->imagecount; $iPost++)

        @isset($igdata[$iPost])
    {{-- @foreach($igdata as $post)  --}}
        <a href="#" data-toggle="modal" data-target="#ig-{{ $igdata[$iPost]['id'] }}" class="ig-grid-item" style="background-image: url('@if($igdata[$iPost]['type'] == 'image') {{ $igdata[$iPost]['url'] }} @else {{ $igdata[$iPost]['thumbnail_url'] }} @endif')">

        </a>
    {{-- @endforeach --}}
        @endisset

    @endfor

</div>


@foreach($igdata as $post) 

<x-cms-modal modalid="ig-{{ $post['id'] }}" modalclass="modal-igpost modal-ig{{ $post['type'] }}" showFooter="0" showHeader="0" centered="1">
   
    <div class="igpost-nav igpost-nav-prev">
        <a href="#" class="bi-arrow-left-circle-fill text-white"></a>
    </div>
    <div class="igpost-nav igpost-nav-next">
        <a href="#" class="bi-arrow-right-circle-fill text-white"></a>
    </div>
    <div class="modal-igpost-outer">
        <div class="modal-igpost-imageframe">

            @switch($post['type'])

                @case('image')

                    <img class="modal-igpost-image" src="{{ $post['url'] }}" /> 

                    @break

                @case('video')

                    <video class="modal-igpost-video" controls poster="{{ $post['thumbnail_url'] }}">
                        
                        <source src="{{ $post['url'] }}"
                                type="video/mp4">

                    </video>

                    @break


            @endswitch



        </div>

        <div class="modal-igpost-text" >
                {{-- Also need to implement some kind of link creation logic (@usernames, #hashtags, and maybe URL detection?) --}}
                {!! nl2br($post['caption']) !!}
        </div>

    </div>

</x-cms-modal>
@endforeach

@push('styles')
    @style('/vendor/ascent/stackeditor/block-assets/ascent-igfeed-core.css')
@endpush

@push('scripts')

    @script('/vendor/ascent/cms/js/jquery.touchSwipe.min.js')

   <script>

        

        $('.modal-igpost').on('hide.bs.modal', function (e) {
           if(vid = $(this).find('video')[0]) {
                vid.pause();
            }
        });

        $('.modal-igpost').on('shown.bs.modal', function (e) {

            $(this).find('.igpost-nav').show();

            $('.modal-igpost').one('click', '.igpost-nav-next', function(e) {
                showNext($(this).parents('.modal-igpost'));
                e.preventDefault();
            });

            $('.modal-igpost').one('click', '.igpost-nav-prev', function(e) {
                showPrev($(this).parents('.modal-igpost'));
                e.preventDefault();
            });

            $(this).one('keydown', function(e) {
                if(e.which == 37) {
                    showPrev(this);
                }
                if(e.which == 39) {
                    showNext(this);
                }
            });

        });


        // if the TouchSwipe library is loaded, enable swipe actions
        if ( $.isFunction($.fn.swipe) ) {
             $('.modal-igpost').swipe( {
                allowPageScroll: 'vertical',
                //Generic swipe handler for all directions
                swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
    
                    switch(direction) {
                    
                    case 'left':
                        showNext(event.currentTarget);
                        break;
                        
                    case 'right':
                        showPrev(event.currentTarget);
                        break;
                    
                    }
                }
                 });
        }

        function showNext(modal) {
            $(modal).find('.igpost-nav').hide();

            $(modal).one('hide.bs.modal', function(e) {
                $(this).next('.modal-igpost').modal('show');
            });

            $(modal).modal('hide');
        }

        function showPrev(modal) {
            $(modal).find('.igpost-nav').hide();

            $(modal).one('hide.bs.modal', function(e) {
                $(this).prev('.modal-igpost').modal('show');
            });

            $(modal).modal('hide');
            
        }

   </script>

@endpush