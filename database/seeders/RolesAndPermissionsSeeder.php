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
