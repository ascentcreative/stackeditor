@extends("stackeditor::block.base.edit")

@section('block-content')
    <div class="wrap {{ $value->clipmask ?? '' }} {{ $value->objectfit ?? '' }}">
        <x-cms-form-croppie label="" name="{{ $name }}[image]" value="{!! isset($value->image) ? $value->image : ''  !!}" wrapper="none"/>
    </div>
@overwrite

@section('block-settings')

    <x-cms-form-input type="text" label="Link to URL" name="{{ $name }}[linkurl]" :value="$value->linkurl ?? ''" />
    <x-cms-form-input type="text" label="Alt Text" name="{{ $name }}[alttext]" :value="$value->alttext ?? ''" />

    <x-cms-form-options type="select" name="{{ $name }}[objectfit]" label="Fit to block" :value="$value->objectfit ?? 'fitwidth'"
            :options="[
                'fitwidth'=>'Fit to block width',
                'contain'=>'Show the Full Image (may leave gaps)',
                'cover'=>'Fill the block (may crop the image)', 
            ]"
            >
        </x-cms-form-options>

    <x-cms-form-options type="select" name="{{ $name }}[clipmask]" label="Clip Mask" :value="$value->clipmask ?? 'none'"
            :options="[
                'none'=>'- None -',
                'circle'=>'Circle'
            ]"
            >
        </x-cms-form-options>


@overwrite          


@section('block-actions')
@overwrite


@push('scripts')
    <script>
        $(document).on('change', '#block-{{ $value->unid }}-settings select', function() {
            // alert($(this).val());
            $(this).parents('.block').find('.wrap').removeClass('circle').removeClass('contain').removeClass('cover').addClass($(this).val());
        });
    </script>
@endpush

@push('styles')
    <style>

        .wrap {
            width: 100%;
            height: 100%;
        }

        .wrap .croppieupload {
            min-height: 100%;
        }

        .wrap .croppieupload img {
            width: 100%;
            height: auto;
            object-position: center;
        }

        .wrap.cover .croppieupload img {
            position: absolute;
            object-fit: cover;
            object-position: center;
            height: 100%;
        }


        .wrap.contain .croppieupload img {
            position: absolute;
            object-fit: contain;
            object-position: center;
            height: 100%;
        }


        .wrap.circle .croppieupload img {
            clip-path: circle();
        }



    </style>
@endpush
