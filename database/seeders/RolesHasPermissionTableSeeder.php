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
     * REGRAS IMPORTANTES:
     * - Programador (role_id 1): Recebe TODAS as permissões automaticamente
     * - Administrador (role_id 2): Recebe TODAS as permissões EXCETO as de ACL
     * - Coordenador (role_id 3): Pode visualizar tudo exceto controle de usuários
     *   - Pode editar seu próprio perfil (Editar Usuário)
     *   - Pode SOMENTE visualizar Pentests e Vulnerabilidades (não criar/editar/excluir)
     *   - NÃO tem acesso a ACL e gerenciamento de usuários
     *
     * Permissões de ACL (restritas ao Programador):
     * - Acessar ACL
     * - Listar Permissões, Criar Permissões, Editar Permissões, Excluir Permissões
     * - Listar Perfis, Criar Perfis, Editar Perfis, Excluir Perfis
     * - Sincronizar Perfis, Atribuir Perfis
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Limpa as associações existentes para evitar duplicação
        DB::table('role_has_permissions')->truncate();

        // Busca todos os perfis
        $programmer = Role::where('name', 'Programador')->first();
        $administrator = Role::where('name', 'Administrador')->first();
        $manager = Role::where('name', 'Gestor')->first();
        $coordinator = Role::where('name', 'Coordenador')->first();

        // Busca todas as permissões existentes no sistema
        $allPermissions = Permission::all();

        // Define as permissões de ACL que devem ser restritas ao Programador
        $aclPermissionNames = [
            'Acessar ACL',
            'Listar Permissões',
            'Criar Permissões',
            'Editar Permissões',
            'Excluir Permissões',
            'Listar Perfis',
            'Criar Perfis',
            'Editar Perfis',
            'Excluir Perfis',
            'Sincronizar Perfis',
            'Atribuir Perfis',
        ];

        // Filtra as permissões excluindo as de ACL
        $permissionsWithoutAcl = $allPermissions->reject(function ($permission) use ($aclPermissionNames) {
            return in_array($permission->name, $aclPermissionNames);
        });

        // Define permissões do Gestor (visualização apenas)
        $managerPermissionNames = [
            'Editar Usuário',              // Pode editar seu próprio perfil
            'Acessar Pentests',            // Visualizar pentests
            'Listar Pentests',
            'Visualizar Pentests',
            'Listar Vulnerabilidades',     // Visualizar vulnerabilidades
            'Visualizar Vulnerabilidades',
        ];

        // Define permissões do Coordenador (visualização apenas)
        $coordinatorPermissionNames = [
            'Editar Usuário',              // Pode editar seu próprio perfil
            'Acessar Pentests',            // Visualizar pentests
            'Listar Pentests',
            'Visualizar Pentests',
            'Listar Vulnerabilidades',     // Visualizar vulnerabilidades
            'Visualizar Vulnerabilidades',
        ];

        $coordinatorPermissions = $allPermissions->filter(function ($permission) use ($coordinatorPermissionNames) {
            return in_array($permission->name, $coordinatorPermissionNames);
        });

        // Atribui TODAS as permissões para o Programador
        if ($programmer) {
            $programmer->syncPermissions($allPermissions);
            echo '✓ Todas as permissões atribuídas ao perfil Programador ('.$allPermissions->count()." permissões)\n";
        }

        // Atribui TODAS as permissões EXCETO ACL para o Administrador
        if ($administrator) {
            $administrator->syncPermissions($permissionsWithoutAcl);
            echo '✓ Permissões atribuídas ao perfil Administrador (exceto ACL: '.$permissionsWithoutAcl->count()." permissões)\n";
        }

        // Atribui permissões de visualização para o Gestor
        if ($manager) {
            $manager->syncPermissions($managerPermissionNames);
            echo '✓ Permissões de visualização atribuídas ao perfil Gestor ('.count($managerPermissionNames)." permissões)\n";
        }

        // Atribui permissões de visualização para o Coordenador
        if ($coordinator) {
            $coordinator->syncPermissions($coordinatorPermissionNames);
            echo '✓ Permissões de visualização atribuídas ao perfil Coordenador ('.count($coordinatorPermissionNames)." permissões)\n";
        }

        // O perfil Usuário não recebe permissões (fica vazio por enquanto)
        echo "✓ Perfis Pentester e Desenvolvedor mantidos sem permissões (a definir)\n";
        echo "\n📊 Total de permissões no sistema: ".$allPermissions->count()."\n";
        echo '📊 Permissões de ACL (restritas ao Programador): '.count($aclPermissionNames)."\n";
    }
}
