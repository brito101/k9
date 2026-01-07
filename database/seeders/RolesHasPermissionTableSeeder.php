<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder atribui permissões aos perfis (roles) do sistema.
     *
     * REGRA IMPORTANTE:
     * - Programador (role_id 1): Recebe TODAS as permissões automaticamente
     * - Administrador (role_id 2): Recebe TODAS as permissões automaticamente
     * - Usuário (role_id 3): Não recebe permissões (vazio por padrão)
     *
     * Sempre que novas permissões forem criadas no PermissionsTableSeeder,
     * elas serão automaticamente atribuídas aos perfis Programador e Administrador.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Limpa as associações existentes para evitar duplicação
        DB::table('role_has_permissions')->truncate();

        // Busca todos os perfis
        $programador = Role::where('name', 'Programador')->first();
        $administrador = Role::where('name', 'Administrador')->first();

        // Busca todas as permissões existentes no sistema
        $permissions = Permission::all();

        // Atribui TODAS as permissões para Programador e Administrador
        if ($programador) {
            $programador->syncPermissions($permissions);
            echo "✓ Todas as permissões atribuídas ao perfil Programador\n";
        }

        if ($administrador) {
            $administrador->syncPermissions($permissions);
            echo "✓ Todas as permissões atribuídas ao perfil Administrador\n";
        }

        // O perfil Usuário não recebe permissões (fica vazio por enquanto)
        echo "✓ Perfil Usuário mantido sem permissões (conforme especificação)\n";
        echo "\n📊 Total de permissões atribuídas: ".$permissions->count()."\n";
    }
}
