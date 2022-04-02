<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CreatePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:make-permission
        {role : Role name.}
        {--settings : Create role for app settings.}
    ';

    protected $guardName;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate laravel backpack permission.';

    protected $permissions = [
        'list',
        'create', 
        'show', 
        'update', 
        'delete', 
        'bulk_delete',
        'export',
        'force_delete',
        'force_bulk_delete',
        'revise',
    ];

    protected $settingsPermissions = [
        'list',
        'create', 
        'update', 
        'delete', 
        'revise',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->guardName = config('backpack.base.guard') ?? 'web';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $roles = $this->argument('role');
        $roles = explode(',', $roles);
        $isSettings = $this->option('settings');

        $rolesAndPermissions = [];
        foreach ($roles as $role) {

            if ($isSettings) {
                $permissions = $this->settingsPermissions;
            }else {
                $permissions = $this->permissions;
            }

            // create role
            $roleInstance = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $this->guardName,
            ]);

            // create permission
            foreach ($permissions as $perm) {
                $rolePermission = $role.'_'.$perm;

                $permission = Permission::firstOrCreate([
                    'name' => $rolePermission,
                    'guard_name' => $this->guardName,
                ]);
                
                // assign role_permission to role
                $permission->assignRole($role);
                
                $this->info('\''.$rolePermission.'\',');
            }
        }

        // super admin ID = 1
        $admin = User::findOrFail(1);

        // Adding permissions via a role
        $admin->assignRole($role);

        // dd($rolesAndPermissions);
    }
}
