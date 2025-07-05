<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        // Existing options...
        'font_dir' => storage_path('fonts'), // Ensure this is the correct directory
        'font_cache' => storage_path('fonts'),
        'default_font' => 'cairo', // Set the default font to the custom font if desired
    ],

    'fonts' => [
        'cairo' => [
            'normal' => storage_path('fonts/Cairo-Regular.ttf'),
            'bold' => storage_path('fonts/Cairo-Bold.ttf'),
            'italic' => storage_path('fonts/Cairo-Light.ttf'), // Use a suitable file
            'bold_italic' => storage_path('fonts/Cairo-Medium.ttf'), // Use a suitable file
        ],
    ],


];
