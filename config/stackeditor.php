<?php

return [

    // nothing here yet...

    // block categories:
    'categories' => [
        'General',
        'Integration',
        'Functionality'
    ],

    // paths to search for TypeDescriptor classes
    'discovery_paths' => [
        app_path() . '/../vendor/ascentcreative/stackeditor/src/TypeDescriptors',
        app_path() . '/../vendor/ascentcreative/cms/src/StackEditor/TypeDescriptors',
        app_path() . '/../vendor/ascentcreative/blog/src/StackEditor/TypeDescriptors',
        app_path() . '/../vendor/ascentcreative/store/src/StackEditor/TypeDescriptors',
        app_path() . '/../vendor/ascentcreative/donate/src/StackEditor/TypeDescriptors',
        app_path() .'/StackEditor/TypeDescriptors',
    ],

    // paths to search for Edit and Show blades for the blocks. Searched in this order.
    'blade_paths' => [
        'stackeditor',
        'stackeditor::block',
        'cms::stackeditor',
        'blog::stackeditor',
        'store::stackeditor',
        'donate::stackeditor',

    ],

    // TypeDescriptor classes to ignore
    'disabled_types' => [
        
    ],

   
];
