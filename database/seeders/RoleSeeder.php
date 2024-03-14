<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On récupère les 2 utilisateurs créés par l'import du fichier SQL
        $user1 = User::find(1)->first();
        $user2 = User::find(2)->first();

        // On récupère le role créé dans PermissionSeeder
        $role = Role::findByName('utilisateur');

        // Et on assigne ce rôle à nos 2 utilisateurs
        $user1->assignRole($role);
        $user1->syncPermissions($role->permissions);
        $user1->save();

        $user2->assignRole($role);
        $user2->syncPermissions($role->permissions);
        $user2->save();

        // Ceci est une version extrêmement simplifiée des rôles et permissions
        // On peut définir des actions pour chaque rôle, mais dans notre cas, on veut uniquement un rôle et pas de permissions
    }
}
