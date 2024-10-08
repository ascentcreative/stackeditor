<?php

use MatthiasMullie\Minify;

use Illuminate\Support\Facades\Storage;

function getStackCSSFile($content, $model) {

    if(is_string($content)) {
        $data = json_decode($content);
    } else {
        $data = $content;
    }
 
    // check if we need to re-create the CSS (based on file date and model->updated_at)
    $path = '/storage/stackeditor/' . $data->unid . '.css';
    $fullpath = $_SERVER['DOCUMENT_ROOT'] . $path;
    $regen = env('STACKEDITOR_FORCECSSREGEN') ?? false;
    if (!file_exists($fullpath)) {
        $regen = true;
    } else {

        if ($model->updated_at->timestamp > filemtime($_SERVER['DOCUMENT_ROOT'] . '/storage/stackeditor/' . $data->unid . '.css')) {
            $regen = true;
        }
    }

    if ($regen) {
        // write to a file
        // normal autoVersion / Minify will kick in on the producced file.
        Storage::disk('public')->put('stackeditor/' . $data->unid . '.css', renderStackCSS($content));      
    }

    return $path;
    
}


$_cssGlossary = [
    'bgcolor' => 'background-color'
];


function renderStackCSS($content) {

    $out = '';
    // foreach row:

    if(is_string($content)) {
        $data = json_decode($content);
    } else {
        $data = $content;
    }

    $rows = $data->rows;
    
    if(isset($rows)) {
        foreach($rows as $row) {
            // output the row's own CSS
            $out .= view('stackeditor::css.row', ['id'=>'row-' . $row->unid, 'data'=>$row]);

            // for each block in the row:
            if(isset($row->blocks)) {
                foreach($row->blocks as $block) {
                    // output the block's CSS
                    $out .= view('stackeditor::css.block', ['id'=>'block-' . $block->unid, 'data'=>$block]);

                }
            }

        }
    }

    return $out;

}


/**
 * @param mixed $model - a model for use in the isApplicable($model) function of the block type descriptor classes
 * 
 * @return array - an associative arrary of block type info (keyed by categories)
 */
function discoverBlockTypes($model=null) : array {

    // first get the array of keys from the StackEditor config:
    $aryBlockTypes = collect(config('stackeditor.categories'))->mapWithKeys(function($item, $key) {
        return [$item => []];
    })->toArray();
    
    foreach(discoverTypeDescriptors($model) as $class) {


        $ref = new  ReflectionClass($class);
        
        // add the necessary data to the array - if:

        // - Not explicitly disabled:
       
        if($class::isApplicable($model)) {
            // add the details to the relevent category in the array
            $aryBlockTypes[$class::getCategory()][] = [
                        'name'=>$class::getName(),
                        'description'=>$class::getDescription(),
                        'bladePath'=>$class::getBladePath(),
                ];


        }
    
    }

    // array_filter to only return the categories which have blocks
    return array_filter($aryBlockTypes);

}


function discoverTypeDescriptors($model = null) : array {

    $types = [];

    // start discovering the classes:
    $paths = config('stackeditor.discovery_paths');

    $disabled = config('stackeditor.disabled_types');
    // dump($paths);

    foreach($paths as $path) {
        // if the folder exists, find all the classes there and instantiate (or maybe we're calling static functions... not sure yet)
        $files = glob($path.'/*.php');

        foreach ($files as $file) {

            $class = \AscentCreative\StackEditor\ReflectionHelper::getClassFullNameFromFile($file);

            $ref = new  ReflectionClass($class);

            if(!in_array($class, $disabled)) {
                // - Not an abstract class
                if (!$ref->isAbstract()) {
                    // - Implements the correct interface (could be by extending the AbstractDescriptor class)
                    if($ref->implementsInterface(\AscentCreative\StackEditor\Contracts\TypeDescriptor::class)) {
                        // - is deemed applicable (perhaps to the supplied model, but there may be wider, global conditions coded in too)
                        $types[] = $class;
                    }
                }
            }

        }

    }

     return $types;

}



function stackeditorBladePaths($type, $action) {

    $paths = config('stackeditor.blade_paths');

    return collect($paths)->transform(function($item) use ($type, $action) {

        return $item . '.' . $type . '.' . $action;

    })->toArray();

}


function resolveDescriptor($type) {

    $map = collect(discoverTypeDescriptors())->mapWithKeys(function($item, $key) {
        $ref = new ReflectionClass($item);
        return [$item::getBladePath() => $item];
    })->toArray();

    return ($map[$type]);

}



function se_addUnits($val, $default='px') {

    $check = (string) ((int) $val); // convert to int and back to string (will lose any supplied units)

    if($check == $val) {
        return $val . $default;
    } else {
        return $val;
    }
}