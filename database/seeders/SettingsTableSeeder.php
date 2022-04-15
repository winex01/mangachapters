<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'         => 'appsettings_log_query',
            'name'        => 'appsettings.log_query',
            'description' => 'Log query in laravel.log file.',
            'value'       => false,
            'field'       => '{"name":"value","label":"Enabled","type":"boolean"}',
            'active'      => 1,
        ],
        [
            'key'         => 'appsettings_attachment_file_limit',
            'name'        => 'appsettings.attachment_file_limit',
            'description' => 'Input file attachment limit.',
            'value'       => 5000,
            'field'       => '{"name":"value","label":"Value in KB","type":"number"}',
            'active'      => 1,
        ],
        [
            'key'         => 'debugbar_enabled',
            'name'        => 'debugbar.enabled',
            'description' => 'Note: if it doesn\'t work run: php artisan optimize:clear. Enable laravel debugbar but only those users that has permission of admin_debugbar can see it.',
            'value'       => false,
            'field'       => '{"name":"value","label":"Enabled","type":"boolean"}',
            'active'      => 0,
        ],
        [
            'key'         => 'appsettings_home_chapters_entries',
            'name'        => 'appsettings.home_chapters_entries',
            'description' => 'Input file home chapter entries limit.',
            'value'       => 30,
            'field'       => '{"name":"value","label":"Value in int","type":"number"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_manga_crud_notice',
            'name'        => 'appsettings.manga_crud_notice',
            'description' => 'Add alert notice to manga crud.',
            'value'       => null,
            'field'       => '{"name":"value","label":"Message","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_app_slogan',
            'name'        => 'appsettings.app_slogan',
            'description' => 'Home navbar slogan.',
            'value'       => 'Bookmark your favorite mangas and get notifications when new chapters are available.',
            'field'       => '{"name":"value","label":"Message","type":"text"}',
            'active'      => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        foreach ($this->settings as $index => $setting) {
            // $result = DB::table('settings')->insert($setting);
            $result = \App\Models\Setting::create($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}
