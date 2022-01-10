@extends("stackeditor::block.base.edit")

@section('block-content')
    <x-cms-form-croppie label="" name="{{ $name }}[image]" value="{!! isset($value->image) ? $value->image : ''  !!}" wrapper="none"/>
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


@overwrite          


@section('block-actions')
@overwrite


@push('scripts')
    <script>
        $(document).on('change', '#block-{{ $value->unid }}-settings select', function() {
            // alert($(this).val());
            $(this).parents('.block').find('.block-data').removeClass('contain').removeClass('cover').addClass($(this).val());
        });
    </script>
@endpush

@push('styles')
    <style>

        .block-data .croppieupload {
            min-height: 100%;
        }

        .block-data .croppieupload img {
            width: 100%;
            height: auto;
            object-position: center;
        }

        .block-data.cover .croppieupload img {
            position: absolute;
            object-fit: cover;
            object-position: center;
            height: 100%;
        }


        .block-data.contain .croppieupload img {
            position: absolute;
            object-fit: contain;
            object-position: center;
            height: 100%;
        }



    </style>
@endpush
