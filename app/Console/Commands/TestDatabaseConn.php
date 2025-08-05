<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class TestDatabaseConn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if the application can connect to the database';


    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing database connection...');

        try {
            $connection = DB::connection();

            $connection->getPdo();

            $dbName = $connection->getDatabaseName();

            $this->info('✅ Successfully connected to the database: ' . $dbName);
        } catch (Throwable $e) {
            $this->error('❌ Could not connect to the database.');
            $this->line('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

}
