
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Misc.
    |--------------------------------------------------------------------------
    */
    'denied_badge'            => '<span class="badge badge-danger">Denied</span>', // 2
    'approved_badge'          => '<span class="badge badge-success">Approved</span>', // 1
    'pending_badge'           => '<span class="badge badge-warning">Pending...</span>', // 0
    'model'                   => 'Model',
    'unsortable_column'       => '*',
    'select_placeholder'      => '-',
    'no_entries'              => 'No entries.',
    'column_title_text_color' => 'text-dark', // list view column title text color
    'duplicate_entry'         => 'Duplicate entry.',    
    'welcome_message'         => 'Welcome Aboard, We hope you enjoy your stay.',
    'notifications'           => 'Notifications',

    // TODO:: create component
    'temp_change_proxy'       => '<span class="text-danger">
                                        PLEASE DONT\'T FORGT TO CHANGE PROXY FILE!!!
                                  </span>
                                  <a class="text-info" href="https://api.proxyscrape.com/v2/?request=getproxies&amp;protocol=http&amp;timeout=10000&amp;country=all&amp;ssl=all&amp;anonymity=all">Download</a>
                                    or
                                  <a class="text-success" target="_blank" href="https://proxyscrape.com/free-proxy-list">Visit Source</a>    
                              ',

    
    /*
    |--------------------------------------------------------------------------
    | Audit Trails Crud
    |--------------------------------------------------------------------------
    */
    'new_value'         => 'New Value',
    'old_value'         => 'Old Value',
    'revisionable'      => 'Revisionable',
    'revisionable_type' => 'Revisionable Type',

    /*
    |--------------------------------------------------------------------------
    | Menu Crud
    |--------------------------------------------------------------------------
    */
    'menus_label' => 'Enter the menus name.',
    'menus_url'   => 'Enter the crud url.',
    'menus_icon'  => 'Enter the icon wrap with the `span` or `i` tag.',

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    'filter_user'            => 'Filter User',
    'users_employee_id_hint' => 'Optional',

    /*
    |--------------------------------------------------------------------------
    | Mangas Crud
    |--------------------------------------------------------------------------
    */
    'mangas_title' => '',

    /*
    |--------------------------------------------------------------------------
    | Mangas Crud
    |--------------------------------------------------------------------------
    */
    'sources_url' => 'Website source',
    'sources_crawler_filter' => 'Tag and class name for filter',
    'sources_description' => 'You can add the description why this is not published or anything.',

    /*
    |--------------------------------------------------------------------------
    | Chapter Crud
    |--------------------------------------------------------------------------
    */
    'chapters_chapter' => '',
    'chapters_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Scan Operation
    |--------------------------------------------------------------------------
    */
    'scan_operation_button' => 'Scan',

    /*
    |--------------------------------------------------------------------------
    | Scan Filters Crud
    |--------------------------------------------------------------------------
    */
    'scan_filters_name' => 'Website url / name. Eg: https://www.readmng.com/',
    'scan_filters_filter' => 'Tag or class name. Eg: .chp_lst a',

    /*
    |--------------------------------------------------------------------------
    | Home 
    |--------------------------------------------------------------------------
    */
    'slogan' => 'Get news to your favorite manga, manghwa, manhua & etc.',

    /*
    |--------------------------------------------------------------------------
    | Chapters 
    |--------------------------------------------------------------------------
    */
    'chapter_recent_chapters'  => 'Recent chapters',
    'chapter_description'      => "Chapter :chapter is out :release.",
    'chapter_mark_as_read'     => 'Mark as read',
    'chapter_mark_all_as_read' => '(Mark all as read)',
    'chapter_bookmark'         => 'Bookmark',
    'chapter_are_you_sure'     => 'Are you sure you want to delete all these items?',
    'chapter_confirm'          => 'Yes, please!',
    'chapter_cancel'           => 'Cancel',
];
