<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->createSuperAdminAccount();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
    }

    private function createSuperAdminAccount()
    {
        foreach (['admin', 'test'] as $name) {
            $email = "$name@$name.com";
            
            $check = User::where('email', $email)->first();

            if (!$check) {
                User::firstOrCreate([
                    'name' => ucwords($name),
                    'email' => $email,
                    'password' => bcrypt('password123'),
                ]);
            }

        }// end foreach
    
    }// end method
}
