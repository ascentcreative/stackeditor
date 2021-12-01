@extends('cms::components.form.wrapper.' . $wrapper)

@php $tmp_label = $label; @endphp

@section('name'){{$name}}@overwrite


@section('element')

    @php 

        // set a default value
    
        if(is_null($value) || $value == '') {

            $obj =  [ 'rows' => [
                    
                        (object)[
        
                                    'type'=>'row',
                                    'bgcolor'=>'transparent',
                                    // 'items'=> [
                                    //     (object) [
                                    //         'type'=>'text',
                                    //         'content'=>''
                                    //     ]

                                    // ]
                        ]

                ]
            ];

            $value = (object) $obj;

            // dd($value);

        }

        $safename = str_replace(array('[', ']'), array('_', ''), $name)

    @endphp

    <div class="stackeditor" id="{{$safename}}" name="{{$name}}">

        <div class="flex flex-between pb-2">

        <div class="btn-group dropright">
            <A class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-toggle="dropdown" >Add Row</A>
            <div class="dropdown-menu dropdown-menu-right" style="">

                <a class="stack-add-row dropdown-item text-sm btn-option" href="#" data-block-type="text" data-block-field="{{ $safename }}">Text</a>
                <a class="stack-add-row dropdown-item text-sm btn-option" href="#" data-block-type="image" data-block-field="{{ $safename }}">Image</a>
                <a class="stack-add-row dropdown-item text-sm btn-option" href="#" data-block-type="video" data-block-field="{{ $safename }}">Video Row</a>
                <div class="dropdown-divider"></div>


                @foreach(config('cms.core_page_blocks') as $key=>$val) 

                    <a class="stack-add-row dropdown-item text-sm btn-option" href="#" data-block-type="{{ $key }}" data-block-field="{{ $safename }}">{{ $val }}</a>

                @endforeach

                @if(count(config('cms.custom_page_blocks')) > 0)

                    <div class="dropdown-divider"></div>

                    @foreach(config('cms.custom_page_blocks') as $key=>$val) 

                        <a class="stack-add-row dropdown-item text-sm btn-option" href="#" data-block-type="{{ $key }}" data-block-field="{{ $safename }}">{{ $val }}</a>

                    @endforeach

                @endif

            </div>
      </div>  

        @if($previewable)
            <button class="btn btn-sm btn-primary bi-eye-fill" id="stack-preview">Preview</button>
        @endif
        
        </div>

        {{-- for each row, show the relevant edit blade --}}
        <div class="stack-rows">

        @if(!isset($value->rows))
    
            @php
                $migrate = [];

                foreach($value as $row) {

                    if(isset($row->items)) {
                        $row->blocks = $row->items;
                        unset($row->items);
                    } else {

                        $row->blocks = [$row];

                    }

                    $migrate['rows'][] = $row;

                }

                $value = (object) $migrate;
            
            @endphp

        @endif

            @foreach($value->rows as $key=>$row)
                
                <x-stackeditor-row name="{{ $safename }}[rows][{{$key}}]" :value="$row">

                    @isset($row->blocks)
                        @foreach($row->blocks as $idx=>$block)

                            <x-stackeditor-block type="{{ $block->type }}" name="{{ $safename }}[rows][{{$key}}][blocks][{{$idx}}]" :value="$block">

                            </x-stackeditor-block>

                        @endforeach
                    @endisset

                </x-stackeditor-row>

            @endforeach

      
        </div>

  

        {{--  --}}


    {{-- 
        This field receives the serialized & stringified JSON on save.
        Using the main field name means that all the actual heirarchical fields are replaced / ignored
    --}}
    <input type="hidden" name="{{$name}}[unid]" value="{{ $value->unid ?? uniqid() }}"/>
    <input type="hidden" name="{{$name}}" class="stack-output"/>
    {{-- <textarea name="{{$name}}" class="stack-output" style="width: 100%; height: 400px"></textarea> --}}

    </div>





@overwrite

@once
    @push('styles')
        @style('/vendor/ascent/stackeditor/ascent-stackeditor.css')
    @endpush
    @push('lib')
        @script('/vendor/ascent/cms/js/jquery.serializejson.js')
        @script('/vendor/ascent/stackeditor/ascent-stackeditor.js')
        @script('/vendor/ascent/stackeditor/ascent-stackeditor-row.js')
    @endpush
@endonce



@push('scripts')

    <script>
      
        $(document).ready(function() {
            $('.stackeditor#{{$safename}}').stackeditor();
        });



        /** prototype previews **/
        $('#stack-preview').click(function() {

            // grab all form data, serialise and post to an endpoint in a popup window. Simples.

            $.ajax({ 
    
                type: 'POST',
                url: '/admin/previewtest',
                data: $('form#frm_edit').serialize(),
                headers: {
                    'Accept' : "application/json"
                }

            }).done(function(data, xhr, request) {

                console.log(data);
                
                // put the resulting HTML in the preview popup.
                w = window.open('', '_preview', "height=800,width=1200");
                w.document.open();
                w.document.write(data);
                w.document.close();

            }).fail(function(data) {
                alert(data.responseJSON.message);
            });

            return false;
        });



    </script>

@endpush

@section('label'){{$tmp_label}}@overwrite