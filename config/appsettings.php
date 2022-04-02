<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config values
    |--------------------------------------------------------------------------
    |
    | This file is for some configuration
    |
    */

    // attachments file limit in KB
    'attachment_file_limit' => 500, 

    // carbon instance format
    'carbon_time_format'             => 'h:i A', // g:i A = 12 hours format, H:i = 24 hour format, use to display time
    'carbon_date_format'             => 'Y-m-d', //
    'carbon_hours_format'            => 'H:i', // eg. 01:15 (hh:mm)
    'carbon_date_hour_minute_format' => 'Y-m-d H:i', // use 24hour format in  hours bec. this format is use in computing

    // date format of entire app
    'date_column_format' => 'text', // input field

    'date_format_field' => 'MM/DD/YYYY', // input field

    // decimal precision
    'decimal_precision' => 2,

    // don't include this columns in exports
    'dont_include_in_exports' => [
        'attachment',
        'email_verified_at',
        'file_link',
        'image',
        'password',
        'photo',
        'remember_token',
    ],

    // file location 
    'how_to_input_days_per_year_file' => 'files/AnnexB.pdf',

    // decimal preciss
    'inputbox_decimal_precision' => 'any',

    //overrided at backpack settings
    'log_query' => env('LOG_QUERY', false),

    // calendar legend boxes color
    'legend_info'      => '#3a87ad',
    'legend_success'   => '#42ba96',
    'legend_primary'   => '#9933cc',
    'legend_warning'   => '#f88804',
    'legend_secondary' => '#f3969a',

    // anchor class color
    'link_color' => 'text-info',
];
