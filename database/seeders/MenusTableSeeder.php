<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'label' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => '<i class="nav-icon la la-dashboard "></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 2,
                'rgt' => 3,
                'depth' => 1,
                'created_at' => '2020-12-16 06:58:25',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            1 => 
            array (
                'id' => 9,
                'label' => 'App Settings',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-cog"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 22,
                'rgt' => 23,
                'depth' => 1,
                'created_at' => '2020-12-16 07:21:07',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            2 => 
            array (
                'id' => 15,
                'label' => 'Administrator Only',
                'url' => NULL,
                'icon' => NULL,
                'permission' => 'admin_view',
                'parent_id' => NULL,
                'lft' => 24,
                'rgt' => 25,
                'depth' => 1,
                'created_at' => '2020-12-16 07:23:11',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            3 => 
            array (
                'id' => 16,
                'label' => 'Audit Trails',
                'url' => 'audittrail',
                'icon' => '<i class=\'nav-icon la la-history\'></i>',
                'permission' => 'audit_trails_list',
                'parent_id' => NULL,
                'lft' => 26,
                'rgt' => 27,
                'depth' => 1,
                'created_at' => '2020-12-16 07:26:27',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            4 => 
            array (
                'id' => 17,
                'label' => 'Authentication',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-users"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 28,
                'rgt' => 35,
                'depth' => 1,
                'created_at' => '2020-12-16 07:27:02',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            5 => 
            array (
                'id' => 18,
                'label' => 'Users',
                'url' => 'user',
                'icon' => '<i class="nav-icon la la-user"></i>',
                'permission' => 'users_list',
                'parent_id' => 17,
                'lft' => 29,
                'rgt' => 30,
                'depth' => 2,
                'created_at' => '2020-12-16 07:27:32',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            6 => 
            array (
                'id' => 19,
                'label' => 'Roles',
                'url' => 'role',
                'icon' => '<i class="nav-icon la la-id-badge"></i>',
                'permission' => 'roles_list',
                'parent_id' => 17,
                'lft' => 31,
                'rgt' => 32,
                'depth' => 2,
                'created_at' => '2020-12-16 07:27:49',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            7 => 
            array (
                'id' => 20,
                'label' => 'Permissions',
                'url' => 'permission',
                'icon' => '<i class="nav-icon la la-key"></i>',
                'permission' => 'permissions_list',
                'parent_id' => 17,
                'lft' => 33,
                'rgt' => 34,
                'depth' => 2,
                'created_at' => '2020-12-16 07:28:08',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            8 => 
            array (
                'id' => 21,
                'label' => 'Advanced',
                'url' => NULL,
                'icon' => '<i class="nav-icon la la-cogs"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 36,
                'rgt' => 45,
                'depth' => 1,
                'created_at' => '2020-12-16 07:28:27',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            9 => 
            array (
                'id' => 22,
                'label' => 'File Manager',
                'url' => 'elfinder',
                'icon' => '<i class="nav-icon la la-files-o"></i>',
                'permission' => 'advanced_file_manager',
                'parent_id' => 21,
                'lft' => 37,
                'rgt' => 38,
                'depth' => 2,
                'created_at' => '2020-12-16 07:30:40',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            10 => 
            array (
                'id' => 23,
                'label' => 'Backups',
                'url' => 'backup',
                'icon' => '<i class=\'nav-icon la la-hdd-o\'></i>',
                'permission' => 'advanced_backups',
                'parent_id' => 21,
                'lft' => 39,
                'rgt' => 40,
                'depth' => 2,
                'created_at' => '2020-12-16 07:31:21',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            11 => 
            array (
                'id' => 25,
                'label' => 'Settings',
                'url' => 'setting',
                'icon' => '<i class=\'nav-icon la la-cog\'></i>',
                'permission' => 'advanced_settings',
                'parent_id' => 21,
                'lft' => 43,
                'rgt' => 44,
                'depth' => 2,
                'created_at' => '2020-12-16 07:32:02',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            12 => 
            array (
                'id' => 26,
                'label' => 'Menu',
                'url' => 'menu',
                'icon' => '<i class=\'nav-icon la la-list\'></i>',
                'permission' => 'menus_list',
                'parent_id' => NULL,
                'lft' => 46,
                'rgt' => 47,
                'depth' => 1,
                'created_at' => '2020-12-16 07:32:42',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            13 => 
            array (
                'id' => 65,
                'label' => 'Web Artisan Tinker',
                'url' => 'tinker',
                'icon' => '<i class=\'nav-icon lab la-laravel\'></i>',
                'permission' => 'admin_web_tinker',
                'parent_id' => NULL,
                'lft' => 48,
                'rgt' => 49,
                'depth' => 1,
                'created_at' => '2021-04-04 23:55:28',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            14 => 
            array (
                'id' => 79,
                'label' => 'Mangas',
                'url' => 'manga',
                'icon' => '<i class="nav-icon las la-book"></i>',
                'permission' => 'mangas_list',
                'parent_id' => NULL,
                'lft' => 4,
                'rgt' => 5,
                'depth' => 1,
                'created_at' => '2022-04-02 12:45:31',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            15 => 
            array (
                'id' => 80,
                'label' => 'Sources',
                'url' => 'source',
                'icon' => '<i class="nav-icon las la-window-restore"></i>',
                'permission' => 'sources_list',
                'parent_id' => NULL,
                'lft' => 18,
                'rgt' => 19,
                'depth' => 1,
                'created_at' => '2022-04-02 13:18:44',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            16 => 
            array (
                'id' => 81,
                'label' => 'Chapters',
                'url' => 'chapter',
                'icon' => '<i class="nav-icon las la-file-alt"></i>',
                'permission' => 'chapters_list',
                'parent_id' => NULL,
                'lft' => 6,
                'rgt' => 7,
                'depth' => 1,
                'created_at' => '2022-04-02 14:27:54',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            17 => 
            array (
                'id' => 82,
                'label' => 'Logs',
                'url' => 'log',
                'icon' => '<i class=\'nav-icon la la-terminal\'></i>',
                'permission' => 'advanced_logs',
                'parent_id' => 21,
                'lft' => 41,
                'rgt' => 42,
                'depth' => 2,
                'created_at' => '2022-04-02 14:32:24',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            18 => 
            array (
                'id' => 83,
                'label' => 'Scan Filters',
                'url' => 'scanfilter',
                'icon' => '<i class="nav-icon las la-microchip"></i>',
                'permission' => 'scan_filters_list',
                'parent_id' => NULL,
                'lft' => 20,
                'rgt' => 21,
                'depth' => 1,
                'created_at' => '2022-04-02 15:18:53',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            19 => 
            array (
                'id' => 84,
                'label' => 'Bookmarks',
                'url' => 'bookmark',
                'icon' => '<i class="nav-icon las la-bookmark"></i>',
                'permission' => 'bookmarks_list',
                'parent_id' => NULL,
                'lft' => 16,
                'rgt' => 17,
                'depth' => 1,
                'created_at' => '2022-04-03 07:32:01',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            20 => 
            array (
                'id' => 85,
                'label' => 'Telescope',
                'url' => 'telescope',
                'icon' => '<i class="nav-icon lab la-laravel"></i>',
                'permission' => 'admin_telescope',
                'parent_id' => NULL,
                'lft' => 50,
                'rgt' => 51,
                'depth' => 1,
                'created_at' => '2022-04-09 19:16:54',
                'updated_at' => '2022-04-22 12:59:34',
            ),
            21 => 
            array (
                'id' => 86,
                'label' => 'Social',
                'url' => 'https://www.facebook.com/ManghwuaHub',
                'icon' => '<i class="nav-icon lab la-facebook"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 14,
                'rgt' => 15,
                'depth' => 1,
                'created_at' => '2022-04-15 03:28:18',
                'updated_at' => '2022-04-22 13:13:03',
            ),
            22 => 
            array (
                'id' => 87,
                'label' => 'About',
                'url' => 'auth/about',
                'icon' => '<i class="nav-icon las la-exclamation-circle"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 8,
                'rgt' => 9,
                'depth' => 1,
                'created_at' => '2022-04-22 12:23:30',
                'updated_at' => '2022-04-22 13:12:42',
            ),
            23 => 
            array (
                'id' => 88,
                'label' => 'Terms',
                'url' => 'auth/terms',
                'icon' => '<i class="nav-icon las la-balance-scale"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 10,
                'rgt' => 11,
                'depth' => 1,
                'created_at' => '2022-04-22 12:58:34',
                'updated_at' => '2022-04-22 13:10:58',
            ),
            24 => 
            array (
                'id' => 89,
                'label' => 'Contact',
                'url' => 'auth/contact',
                'icon' => '<i class="nav-icon las la-envelope"></i>',
                'permission' => NULL,
                'parent_id' => NULL,
                'lft' => 12,
                'rgt' => 13,
                'depth' => 1,
                'created_at' => '2022-04-22 12:59:19',
                'updated_at' => '2022-04-22 13:10:26',
            ),
        ));
        
        
    }
}