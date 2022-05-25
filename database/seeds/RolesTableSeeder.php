<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $owner = Role::create(['name' => 'owner']);
        $user = Role::create(['name' => 'user']);

        $listProjects = Permission::create(['name' => 'list projects']);
        $createProjects = Permission::create(['name' => 'create projects']);
        $editProjects = Permission::create(['name' => 'edit projects']);
        $trashProjects = Permission::create(['name' => 'trash projects']);
        $deleteProjects = Permission::create(['name' => 'delete projects']);

        $listProposals = Permission::create(['name' => 'list proposals']);
        $createProposals = Permission::create(['name' => 'create proposals']);
        $editProposals = Permission::create(['name' => 'edit proposals']);
        $trashProposals = Permission::create(['name' => 'trash proposals']);
        $deleteProposals = Permission::create(['name' => 'delete proposals']);

        $listUsers = Permission::create(['name' => 'list users']);
        $createUsers = Permission::create(['name' => 'create users']);
        $editUsers = Permission::create(['name' => 'edit users']);
        $deleteUsers = Permission::create(['name' => 'delete users']);

        $listCategories = Permission::create(['name' => 'list categories']);
        $createCategories = Permission::create(['name' => 'create categories']);
        $editCategories = Permission::create(['name' => 'edit categories']);
        $deleteCategories = Permission::create(['name' => 'delete categories']);

        $viewDashboard = Permission::create(['name' => 'view dashboard']);

        $listMedia = Permission::create(['name' => 'list media']);
        $viewMedia = Permission::create(['name' => 'view media']);
        $createMedia = Permission::create(['name' => 'create media']);
        $editMedia = Permission::create(['name' => 'edit media']);
        $trashMedia = Permission::create(['name' => 'trash media']);
        $deleteMedia = Permission::create(['name' => 'delete media']);

        $listRoles = Permission::create(['name' => 'list roles']);
        $createRoles = Permission::create(['name' => 'create roles']);
        $editRoles = Permission::create(['name' => 'edit roles']);
        $deleteRoles = Permission::create(['name' => 'delete roles']);
        $assignRoles = Permission::create(['name' => 'assign roles']);

        $admin->syncPermissions([
            $viewDashboard,
            $listProjects, $createProjects, $editProjects, $trashProjects, $deleteProjects,
            $listProposals, $createProposals, $editProposals, $trashProposals, $deleteProposals,
            $listCategories, $createCategories, $editCategories, $deleteCategories,
            $listUsers, $createUsers, $editUsers, $deleteUsers,
            $listMedia, $viewMedia, $createMedia, $editMedia, $trashMedia, $deleteMedia,
            $listRoles, $createRoles, $editRoles, $deleteRoles, $assignRoles
        ]);
        $owner->syncPermissions([
            $viewDashboard,
            $listProjects, $createProjects, $editProjects, $trashProjects, $deleteProjects,
            $listProposals, $createProposals, $editProposals, $trashProposals, $deleteProposals,
            $listUsers, $createUsers, $editUsers,
            $listMedia, $viewMedia, $createMedia, $editMedia, $trashMedia, $deleteMedia
        ]);
        $user->syncPermissions([
            $viewDashboard
        ]);
    }
}
