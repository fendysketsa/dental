<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create banks']);
        Permission::create(['name' => 'store banks']);
        Permission::create(['name' => 'destroy banks']);
        Permission::create(['name' => 'update banks']);
        Permission::create(['name' => 'show banks']);

        Permission::create(['name' => 'create news']);
        Permission::create(['name' => 'store news']);
        Permission::create(['name' => 'destroy news']);
        Permission::create(['name' => 'update news']);
        Permission::create(['name' => 'show news']);

        Permission::create(['name' => 'create brands']);
        Permission::create(['name' => 'store brands']);
        Permission::create(['name' => 'destroy brands']);
        Permission::create(['name' => 'update brands']);
        Permission::create(['name' => 'show brands']);

        Permission::create(['name' => 'create branchs']);
        Permission::create(['name' => 'store branchs']);
        Permission::create(['name' => 'destroy branchs']);
        Permission::create(['name' => 'update branchs']);
        Permission::create(['name' => 'show branchs']);

        Permission::create(['name' => 'create discounts']);
        Permission::create(['name' => 'store discounts']);
        Permission::create(['name' => 'destroy discounts']);
        Permission::create(['name' => 'update discounts']);
        Permission::create(['name' => 'show discounts']);

        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'store categories']);
        Permission::create(['name' => 'destroy categories']);
        Permission::create(['name' => 'update categories']);
        Permission::create(['name' => 'show categories']);

        Permission::create(['name' => 'create services']);
        Permission::create(['name' => 'store services']);
        Permission::create(['name' => 'destroy services']);
        Permission::create(['name' => 'update services']);
        Permission::create(['name' => 'show services']);

        Permission::create(['name' => 'create locations']);
        Permission::create(['name' => 'store locations']);
        Permission::create(['name' => 'destroy locations']);
        Permission::create(['name' => 'update locations']);
        Permission::create(['name' => 'show locations']);

        Permission::create(['name' => 'create members']);
        Permission::create(['name' => 'store members']);
        Permission::create(['name' => 'destroy members']);
        Permission::create(['name' => 'update members']);
        Permission::create(['name' => 'show members']);

        Permission::create(['name' => 'create packages']);
        Permission::create(['name' => 'store packages']);
        Permission::create(['name' => 'destroy packages']);
        Permission::create(['name' => 'update packages']);
        Permission::create(['name' => 'show packages']);

        Permission::create(['name' => 'create employees']);
        Permission::create(['name' => 'store employees']);
        Permission::create(['name' => 'destroy employees']);
        Permission::create(['name' => 'update employees']);
        Permission::create(['name' => 'show employees']);

        Permission::create(['name' => 'create promos']);
        Permission::create(['name' => 'store promos']);
        Permission::create(['name' => 'destroy promos']);
        Permission::create(['name' => 'update promos']);
        Permission::create(['name' => 'show promos']);

        Permission::create(['name' => 'create slides']);
        Permission::create(['name' => 'store slides']);
        Permission::create(['name' => 'destroy slides']);
        Permission::create(['name' => 'update slides']);
        Permission::create(['name' => 'show slides']);

        Permission::create(['name' => 'create shifts']);
        Permission::create(['name' => 'store shifts']);
        Permission::create(['name' => 'destroy shifts']);
        Permission::create(['name' => 'update shifts']);
        Permission::create(['name' => 'show shifts']);

        Permission::create(['name' => 'create suppliers']);
        Permission::create(['name' => 'store suppliers']);
        Permission::create(['name' => 'destroy suppliers']);
        Permission::create(['name' => 'update suppliers']);
        Permission::create(['name' => 'show suppliers']);

        Permission::create(['name' => 'create vouchers']);
        Permission::create(['name' => 'store vouchers']);
        Permission::create(['name' => 'destroy vouchers']);
        Permission::create(['name' => 'update vouchers']);
        Permission::create(['name' => 'show vouchers']);

        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'store products']);
        Permission::create(['name' => 'destroy products']);
        Permission::create(['name' => 'update products']);
        Permission::create(['name' => 'show products']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'manager']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'terapis']);

        $role = Role::create(['name' => 'kasir']);

        $role = Role::create(['name' => 'owner']);

        $role->givePermissionTo('create products');
    }
}
