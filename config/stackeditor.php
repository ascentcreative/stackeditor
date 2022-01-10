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
        app_path() .'/StackEditor/TypeDescriptors',
    ],

    // paths to search for Edit and Show blades for the blocks. Searched in this order.
    'blade_paths' => [
        'stackeditor',
        'stackeditor::block',
        'cms::stackeditor',
        'blog::stackeditor',
    ],

    // TypeDescriptor classes to ignore
    'disabled_types' => [
        
    ],

    'breakpoints' => [
        'lg' => [
            'width'=>'1400px',
            'cols'=>12
        ],
        'md' => [
            'width'=>'800px',
            'cols'=>8
        ],
        'sm' => [
            'width'=>'500px',
            'cols'=>4
        ],
    ]

   
];
