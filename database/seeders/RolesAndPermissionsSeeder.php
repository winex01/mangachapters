<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * 
     */
    protected $rolesAndPermissions = [

        'admin' => [
            'admin_view',
            'admin_trashed_filter', 
            'admin_debugbar', 
            'admin_web_tinker', 
            'admin_notify_newly_registered',
            'admin_notify_newly_created_manga',
            'admin_proxy_notice',
            'admin_telescope',
            'admin_widgets',
            'admin_received_contact_us',
            'admin_reply_contact_us',
        ],

        'audit_trails' => [
            'audit_trails_list',
            'audit_trails_show', 
            'audit_trails_delete',
            'audit_trails_bulk_delete',
            'audit_trails_export',
            'audit_trails_restore_revise',
            'audit_trails_bulk_restore_revise', 
        ],

        'users' => [
            'users_list',
            'users_create', 
            'users_update', 
            'users_delete', 
            'users_export', 
            'users_revise',
            'users_force_delete',
        ],

        'roles' => [
            'roles_list',
            'roles_create', 
            'roles_update', 
            'roles_delete', 
        ],

        'permissions' => [
            'permissions_list',
            'permissions_create', 
            'permissions_update', 
            'permissions_delete', 
        ],
       
        'advanced' => [
            'advanced_file_manager',
            'advanced_backups',
            'advanced_logs',
            'advanced_settings',
        ],

        'menus' => [
            'menus_list',
            'menus_create',
            'menus_reorder',
            'menus_update',
            'menus_delete',
        ],

        'mangas' => [
            'mangas_list',
            'mangas_create',
            'mangas_show',
            'mangas_update',
            'mangas_delete',
            'mangas_bulk_delete',
            'mangas_export',
            'mangas_force_delete',
            'mangas_force_bulk_delete',
            'mangas_revise',
            'mangas_bookmark_toggle',
            'mangas_bulk_bookmark',
            'mangas_slug',
            'mangas_last_chapter_release_filter',
            'mangas_add_manga',
            
        ],

        'sources' => [
            'sources_list',
            'sources_create',
            'sources_show',
            'sources_update',
            'sources_delete',
            'sources_bulk_delete',
            'sources_export',
            'sources_force_delete',
            'sources_force_bulk_delete',
            'sources_revise',
        ],

        'chapters' => [
            'chapters_list',
            'chapters_create',
            'chapters_show',
            'chapters_update',
            'chapters_delete',
            'chapters_bulk_delete',
            'chapters_export',
            'chapters_force_delete',
            'chapters_force_bulk_delete',
            'chapters_revise',
            'chapters_scan',
            'chapters_invalid_link',
        ],

        'scan_filters' => [
            'scan_filters_list',
            'scan_filters_create',
            'scan_filters_show',
            'scan_filters_update',
            'scan_filters_delete',
            'scan_filters_bulk_delete',
            'scan_filters_export',
            'scan_filters_force_delete',
            'scan_filters_force_bulk_delete',
            'scan_filters_revise',
        ],

        'bookmarks' => [
            'bookmarks_list',
            'bookmarks_export',
            'bookmarks_bookmark_toggle',
        ],

        'notifications' => [
            'notifications_list'
        ],
    ];

    /**
     * if backpack config is null 
     * then default is web
     */
    public $guardName;

    /**
     * 
     */
    public function __construct()
    {
        $this->guardName = config('backpack.base.guard') ?? 'web';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create specific permissions
        $this->createRolesAndPermissions();

        // assign all roles define in config/seeder to admin
        $this->assignAllRolesToAdmin();

    }

    private function assignAllRolesToAdmin()
    {
        // super admin ID = 1
        $admin = User::findOrFail(1);

        $roles = collect($this->rolesAndPermissions)->keys()->unique()->toArray();
        $admin->syncRoles($roles);
    }

    private function createRolesAndPermissions()
    {
        foreach ($this->rolesAndPermissions as $role => $permissions){
            // create role
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $this->guardName,
            ]);

            foreach ($permissions as $rolePermission) {
               $permission = Permission::firstOrCreate([
                    'name' => $rolePermission,
                    'guard_name' => $this->guardName,
                ]);
                
                // assign role_permission to role
               $permission->assignRole($role);
            }
        }

    }
}
