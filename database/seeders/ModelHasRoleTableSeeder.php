<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ModelHasRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Se APP_ENV = local: Atribui roles para todos os usuários fake
     * Se APP_ENV = production: Atribui role apenas para o Programador
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $isLocal = env('APP_ENV') === 'local';
        
        $count = 0;

        // Sempre atribui role para o Programador
        $programador = User::where('email', env('PROGRAMMER_EMAIL'))->first();
        if ($programador) {
            $programador->assignRole('Programador');
            $count++;
            echo "✓ Role 'Programador' atribuída para: {$programador->email}\n";
        }

        // Se estiver em local, atribui roles para os demais usuários fake
        if ($isLocal) {
            $administrador = User::where('email', 'administrador@base.com')->first();
            if ($administrador) {
                $administrador->assignRole('Administrador');
                $count++;
                echo "✓ Role 'Administrador' atribuída para: {$administrador->email}\n";
            }

            $coordenador = User::where('email', 'coordenador@base.com')->first();
            if ($coordenador) {
                $coordenador->assignRole('Coordenador');
                $count++;
                echo "✓ Role 'Coordenador' atribuída para: {$coordenador->email}\n";
            }

            $pentester = User::where('email', 'pentester@base.com')->first();
            if ($pentester) {
                $pentester->assignRole('Pentester');
                $count++;
                echo "✓ Role 'Pentester' atribuída para: {$pentester->email}\n";
            }

            $desenvolvedor = User::where('email', 'desenvolvedor@base.com')->first();
            if ($desenvolvedor) {
                $desenvolvedor->assignRole('Desenvolvedor');
                $count++;
                echo "✓ Role 'Desenvolvedor' atribuída para: {$desenvolvedor->email}\n";
            }

            echo "\n✓ Ambiente LOCAL detectado - {$count} roles atribuídas com sucesso\n";
        } else {
            echo "\n✓ Ambiente PRODUCTION detectado - {$count} role atribuída com sucesso\n";
        }
    }
}
