<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetDatabasePath extends Command
{
    protected $signature = 'db:set-path';
    protected $description = 'Sets the absolute path for the SQLite database in the .env file';

    public function handle()
    {
        $databasePath = base_path('database/database.sqlite');

        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $newEnvContent = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE='.$databasePath, $envContent);

        if ($envContent === $newEnvContent) {
            $newEnvContent .= "\nDB_DATABASE=$databasePath";
        }
        File::put($envPath, $newEnvContent);

        $this->info('The database path has been set successfully in the .env file.');
    }
}
