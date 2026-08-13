<?php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$permissions = ['departments.view', 'departments.create', 'departments.edit', 'departments.delete'];

foreach ($permissions as $p) {
    Permission::firstOrCreate(['name' => $p]);
}

$role = Role::where('name', 'Super Admin')->orWhere('name', 'Admin')->first();
if ($role) {
    $role->givePermissionTo($permissions);
}

echo "Permissions created and assigned successfully.\n";
