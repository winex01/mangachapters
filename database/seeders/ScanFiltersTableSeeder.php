<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScanFiltersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('scan_filters')->delete();
        
        \DB::table('scan_filters')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'https://www.readmng.com/',
                'filter' => '.chp_lst a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:26:14',
                'updated_at' => '2022-04-02 18:55:36',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'https://mangakakalot.com/',
                'filter' => '.chapter-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:13',
                'updated_at' => '2022-04-02 18:55:23',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'https://manganato.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:34',
                'updated_at' => '2022-04-02 18:55:06',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'https://www.topmanhua.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:50',
                'updated_at' => '2022-04-02 18:54:52',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'https://mangatx.com/',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 16:37:10',
                'updated_at' => '2022-04-02 18:54:33',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'https://readmanganato.com/',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 16:57:09',
                'updated_at' => '2022-04-02 18:54:14',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'https://mangaraw.pro/',
                'filter' => '.clstyle a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 18:10:24',
                'updated_at' => '2022-04-02 18:18:57',
            ),
        ));
        
        
    }
}