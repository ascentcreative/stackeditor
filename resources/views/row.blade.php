@php 
    $rowid = "row-" . uniqid();
@endphp

<div class="row-edit" id="{{ $rowid }}">

    <div class="row-handle bi-arrow-down-up">

    </div>

    <div class="row-content blocks" id="" style="width: 100%;">

        {{ $slot }}
        {{-- @yield('row-content') --}}
    </div>

    <div class="row-settings">

        <div class="controls">

            {{-- <div class="btn-group dropleft">
                <A href="#" class="row-add-block-menu bi-plus-circle-fill" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></A>
                <div class="dropdown-menu">
                    <A href="#" class="row-add-block dropdown-item bi-card-text" data-block-type="text"> Text</A>
                    <A href="#" class="row-add-block dropdown-item bi-card-image" data-block-type="image"> Image</A>
                    <A href="#" class="row-add-block dropdown-item bi-camera-reels-fill" data-block-type="video"> Video</A>
                </div>
            </div>
          --}}

          
            <div style="flex-basis: 100%; flex-shrink: 1">
                @yield('row-actions')
            </div>
         

            <A href="#" class="row-open-settings bi-gear" data-toggle="modal" data-target="#{{ $rowid }}-settings"></A>

            <A href="#" class="row-delete bi-trash"></A>

        </div>

        {{-- @dump($defaults); --}}
      
        {{-- Wrap settings in a modal --}}
        <x-cms-modal modalid="{{ $rowid }}-settings" title="Row Settings" :closebutton="false">

            <div class="container">
            @section('row-settings')
            
               
            @show

                <x-cms-form-checkbox type="" name="{{ $name }}[published]" label="Published?" uncheckedValue="0" checkedValue="1" :value="$value->published ?? 1"/>

                <x-cms-form-input type="text" name="{{ $name }}[contentwidth]" label="Content Width" :value="$value->contentwidth ?? ($defaults['contentwidth'] ?? '')">
                    The width of the screen to use for the content. Leave blank for the default centralised portion, or enter values in % or px. <br/>
                    <strong>Examples:<br/></strong>
                    <code>100%</code> will use the full screen width.<br/>
                    <code>500px</code> will use the central 500px of the screen (or shrink if narrower)
                </x-cms-form-input>

                {{-- <x-cms-form-checkbox type="" name="{{ $name }}[fullwidth]" label="Full Width?" :value="$value->fullwidth ?? ''">
                </x-cms-form-checkbox> --}}

                <x-cms-form-colour label="Background Colour" name="{{ $name }}[bgcolor]" :value="$value->bgcolor ?? ($defaults['bgcolor'] ?? 'transparent')" elementClass="row-bgcolor"/>

                <x-cms-form-colour label="Content Background Colour" name="{{ $name }}[contentbgcolor]" :value="$value->contentbgcolor ?? ($defaults['contentbgcolor'] ?? 'transparent')" elementClass="row-contentbgcolor"/>

                <x-cms-form-fileupload label="Background Image" name="{{ $name }}[bgimage]" :value="$value->bgimage ?? ''" />

                <div class="border p-2 mb-2">
                    <div><strong>Padding</strong></div>
                    <x-cms-form-input type="text" name="{{ $name }}[padding][top]" label="Top" :value="$value->padding->top ?? ($defaults['padding-top'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[padding][bottom]" label="Bottom" :value="$value->padding->bottom ?? ($defaults['padding-bottom'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[padding][left]" label="Left" :value="$value->padding->left ?? ($defaults['padding-left'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[padding][right]" label="Right" :value="$value->padding->right ?? ($defaults['padding-right'] ?? 0)"/>
                </div>

                <div class="border p-2 mb-2">
                    <div><strong>Margin</strong></div>
                    <x-cms-form-input type="text" name="{{ $name }}[margin][top]" label="Top" :value="$value->margin->top ?? ($defaults['margin-top'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[margin][bottom]" label="Bottom" :value="$value->margin->bottom ?? ($defaults['margin-bottom'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[margin][left]" label="Left" :value="$value->margin->left ?? ($defaults['margin-left'] ?? 0)"/>
                    <x-cms-form-input type="text" name="{{ $name }}[margin][right]" label="Right" :value="$value->margin->right ?? ($defaults['margin-right'] ?? 0)"/>
                </div>


                <div class="border p-2">
                    <div><strong>Block Display Options</strong></div>
                    <x-cms-form-options type="select" name="{{ $name }}[alignitems]" label="Align Items" :value="$value->alignitems ?? ($defaults['alignitems'] ?? 'normal')"
                        :options="[
                            'normal'=>'Normal',
                            'flex-start'=>'Top',
                            'center'=>'Middle',
                            'flex-end'=>'Bottom',    
                            'stretch'=>'Match Heights'
                        ]"
                        />

                        <x-cms-form-options type="select" name="{{ $name }}[collapseorder]" label="Collapse Order" :value="$value->collapseorder ?? ($defaults['collapseorder'] ?? 'left-to-right')"
                            :options="[
                                'left-to-right'=>'Left to Right',
                                'right-to-left'=>'Right to Left', 
                            ]"
                            >
                            The order the blocks will drop into a column on mobile.
                        </x-cms-form-options>


                </div>
        



            </div>

            <x-slot name="footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"> OK </button>
            </x-slot>
            
        </x-cms-modal>
        {{-- End modal --}}
    
        {{-- required hidden fields --}}
        <input type="hidden" class="row-unid" name="{{ $name }}[unid]" value="{{ $value->unid ?? uniqid() }}" />
        {{-- <INPUT type="hidden" name="{{$name}}[type]" value="{{$type}}" /> --}}

    </div>


</div>

@push('scripts')

<script>

    $(document).ready(function() { 
        //alert('ok');
        $('#{{ $rowid }}').stackeditorrow();        
    });

    
    var darkOrLight = function(red, green, blue) {
        var brightness;
        brightness = (red * 299) + (green * 587) + (blue * 114);
        brightness = brightness / 255000;

        // values range from 0 to 1
        // anything greater than 0.5 should be bright enough for dark text
        if (brightness >= 0.5) {
            return "dark-text";
        } else {
            return "light-text";
        }
    }

</script>


@endpush