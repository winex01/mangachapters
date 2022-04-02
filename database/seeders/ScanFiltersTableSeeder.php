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
                'name' => 'www.readmng.com',
                'filter' => '.chp_lst a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:26:14',
                'updated_at' => '2022-04-02 15:26:14',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'www.mangakakalot.com',
                'filter' => '.chapter-list a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:13',
                'updated_at' => '2022-04-02 15:27:13',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'www.manganato.com',
                'filter' => '.row-content-chapter a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:34',
                'updated_at' => '2022-04-02 15:27:34',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'www.topmanhua.com',
                'filter' => '.version-chap a',
                'deleted_at' => NULL,
                'created_at' => '2022-04-02 15:27:50',
                'updated_at' => '2022-04-02 15:27:50',
            ),
        ));
        
        
    }
}