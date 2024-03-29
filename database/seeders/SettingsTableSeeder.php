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
            'value'       => 'Contact us in discord, if you want your favorite Manga/Novels to be added.',
            'field'       => '{"name":"value","label":"Message","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_app_slogan',
            'name'        => 'appsettings.app_slogan',
            'description' => 'Home navbar slogan.',
            'value'       => 'A bookmark notification system for your favorite mangas across multiple different sources.',
            'field'       => '{"name":"value","label":"Message","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_app_version',
            'name'        => 'appsettings.app_version',
            'description' => 'App version put the github release tag here.',
            'value'       => '1.0.0',
            'field'       => '{"name":"value","label":"Version","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_social_media',
            'name'        => 'appsettings.social_media',
            'description' => 'Change social media links at guest.',
            'value'       => 'https://www.facebook.com/ManghwuaHub',
            'field'       => '{"name":"value","label":"Link","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_discord_link',
            'name'        => 'appsettings.discord_link',
            'description' => 'Change discord server links at guest.',
            'value'       => 'https://discord.gg/hFU28nF8FU',
            'field'       => '{"name":"value","label":"Link","type":"text"}',
            'active'      => 1,
        ],

        [
            'key'         => 'appsettings_dashboard_notice',
            'name'        => 'appsettings.dashboard_notice',
            'description' => 'Add alert-danger notice to dashboard.',
            'value'       => '',
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
