@php

    if(!$block->account) {
        return;
    }
    
    $profile = \Dymantic\InstagramFeed\Profile::find($block->account); //where('username', 'ascent_creative')->first();



    $igdata = $profile->refreshFeed($block->imagecount ?? 5);
    
@endphp

<div class="ig-grid">

    @foreach($igdata as $post) 
        <div class="ig-grid-item" style="background-image: url('{{ $post['url'] }}')">

        </div>
    @endforeach

</div>

@push('styles')
    @style('/vendor/ascent/cms/css/ascent-igfeed-core.css')
@endpush