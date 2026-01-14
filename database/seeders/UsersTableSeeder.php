<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Se APP_ENV = local: Cria todos os usuários (Programador do .env + demais com dados fake)
     * Se APP_ENV = production: Cria apenas o usuário Programador do .env
     *
     * @return void
     */
    public function run()
    {
        $isLocal = env('APP_ENV') === 'local';

        // Sempre cria o Programador (vem do .env)
        $users = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Programador',
                'email' => env('PROGRAMMER_EMAIL'),
                'password' => bcrypt(env('PROGRAMMER_PASSWD')),
                'created_at' => new DateTime('now'),
            ],
        ];

        // Se estiver em local, adiciona os demais usuários com dados fake
        if ($isLocal) {
            $users = array_merge($users, [
                [
                    'id' => Str::uuid()->toString(),
                    'name' => 'Administrator',
                    'email' => 'administrador@base.com',
                    'password' => bcrypt('12345678'),
                    'created_at' => new DateTime('now'),
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'name' => 'Coordenador Silva',
                    'email' => 'coordenador@base.com',
                    'password' => bcrypt('12345678'),
                    'created_at' => new DateTime('now'),
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'name' => 'João Pentester',
                    'email' => 'pentester@base.com',
                    'password' => bcrypt('12345678'),
                    'created_at' => new DateTime('now'),
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'name' => 'Maria Desenvolvedora',
                    'email' => 'desenvolvedor@base.com',
                    'password' => bcrypt('12345678'),
                    'created_at' => new DateTime('now'),
                ],
            ]);

            echo "✓ Ambiente LOCAL detectado - Criando todos os usuários (Programador + 4 usuários fake)\n";
        } else {
            echo "✓ Ambiente PRODUCTION detectado - Criando apenas o usuário Programador\n";
        }

        DB::table('users')->insert($users);
        echo '✓ '.count($users)." usuário(s) criado(s) com sucesso\n";
    }
}
