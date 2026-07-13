<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RevokeUser extends Command
{
    protected $signature = 'usuarios:revocar {email}';

    protected $description = 'Quita el acceso de una persona autorizada.';

    public function handle(): int
    {
        $email = trim((string) $this->argument('email'));
        $deleted = User::where('email', $email)->delete();

        if ($deleted === 0) {
            $this->warn("No existe un usuario autorizado con el correo {$email}.");

            return self::SUCCESS;
        }

        $this->info("Acceso revocado: {$email}");

        return self::SUCCESS;
    }
}
