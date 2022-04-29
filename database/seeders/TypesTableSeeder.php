<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('types')->delete();
        
        \DB::table('types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Manga',
                'created_at' => '2022-04-29 21:09:40',
                'updated_at' => '2022-04-29 21:09:40',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Novel',
                'created_at' => '2022-04-29 21:09:40',
                'updated_at' => '2022-04-29 21:09:40',
            ),
        ));
        
        
    }
}