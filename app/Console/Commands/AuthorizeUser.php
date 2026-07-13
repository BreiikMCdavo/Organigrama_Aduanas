<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizeUser extends Command
{
    protected $signature = 'usuarios:autorizar {email} {password} {--name=}';

    protected $description = 'Crea o actualiza una persona autorizada para ingresar al sistema.';

    public function handle(): int
    {
        $email = trim((string) $this->argument('email'));
        $password = (string) $this->argument('password');
        $name = trim((string) $this->option('name')) ?: $email;

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ],
        );

        $this->info("Usuario autorizado: {$email}");

        return self::SUCCESS;
    }
}
