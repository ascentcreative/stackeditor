<?php

Route::middleware(['web'])->namespace('AscentCreative\CMS\Controllers')->group(function () {


    Route::prefix('admin')->namespace('Admin')->middleware(['useAdminLogin', 'auth', 'can:administer'])->group(function() {

        // rows have an initial block type.
        Route::get('/stack/make-row/{type}/{name}/{key}', function($type, $name, $key) {
            return view('stackeditor::stack.make.row')->with('type', $type)->with('name', $name)->with('key', $key)->with('value', null);
        });

        // make a block to add to an existing row.
        Route::get('/stack/make-block/{type}/{name}/{cols}', function($type, $name, $cols) {
            return view('stackeditor::stack.make.block')->with('type', $type)->with('name', $name)->with('cols', $cols); 
        });

    });

});
