
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
    'chapter_notification_description' => "Chapter :chapter is out :release.",
    'chapter_notification_image'       => '<img style="height: 50px; width:40px;" src=":src" class="rounded" alt="...">',
    'chapter_notification_title'       => '<strong class="d-block text-gray-dark">:title</strong>',
    'chapter_notification_card' => '
        <div class="media text-muted pt-3 col-md-4">
          <img style="height: 50px; width:40px;" src=":image" class="rounded" alt="...">
          <p class="ml-2 media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <strong class="d-block text-gray-dark">:title</strong>
            :link
          </p>
        </div>
    ',

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
];
