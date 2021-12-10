@extends("stackeditor::block.base.edit")


@section('block-content')


    <div class="flex flex-center" style="flex: 0 0 200px">
        <H2 class="text-muted text-center" style="width: 100%;">INSTAGRAM FEED</H2>
    </div>

    <div class="container">

        <x-cms-form-foreignkeyselect type="select" name="{{ $name }}[account]" value="{{ $value->account ?? ''}}" label="Account" 
            :query="\Dymantic\InstagramFeed\Profile::query()" labelField="username"
            />

        <x-cms-form-input type="number" name="{{ $name }}[imagecount]" value="{{ $value->imagecount ?? 5 }}" label="Images to Show" />

    </div>


@overwrite


@section('block-settings')
@overwrite

@section('block-actions')
@overwrite