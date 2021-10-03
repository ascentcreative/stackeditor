<?php

use MatthiasMullie\Minify;

use Illuminate\Support\Facades\Storage;

function getStackCSSFile($content, $model) {

    $data = json_decode($content);
 
    // check if we need to re-create the CSS (based on file date and model->updated_at)
    $path = '/storage/stackeditor/' . $data->unid . '.css';
    $fullpath = $_SERVER['DOCUMENT_ROOT'] . $path;
    $regen = false;
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
    foreach(json_decode($content)->rows as $row) {
        // output the row's own CSS
        $out .= view('stackeditor::css', ['id'=>'row-' . $row->unid, 'data'=>$row]);

        // for each block in the row:

        foreach($row->blocks as $block) {
            // output the block's CSS
            $out .= view('stackeditor::css', ['id'=>'block-' . $block->unid, 'data'=>$block]);

        }

    }

    return $out;


}