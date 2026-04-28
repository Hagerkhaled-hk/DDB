<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FreshMigrateAll extends Command
{
    protected $signature = 'migrate:fresh-all {--seed : Seed after migrating}';
    protected $description = 'Drop all tables on all connections and re-run migrations';

    // List every connection used in your project
    protected array $connections = ['mysql', 'branch_A', 'branch_B'];

    public function handle(): int
    {
        if (!$this->confirm('This will drop ALL tables on ALL connections. Continue?', true)) {
            return self::FAILURE;
        }

        foreach ($this->connections as $connection) {
            $this->info("Dropping all tables on [{$connection}]...");
            $this->dropAllTablesOn($connection);
        }

        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        if ($this->option('seed')) {
            $this->call('db:seed', ['--force' => true]);
        }

        $this->info('Done!');
        return self::SUCCESS;
    }

    protected function dropAllTablesOn(string $connection): void
    {
        $db = DB::connection($connection);
        $dbName = $db->getDatabaseName();

        // Disable FK checks so drops don't fail due to constraints
        $db->statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = $db->select(
            "SELECT table_name FROM information_schema.tables 
             WHERE table_schema = ? AND table_type = 'BASE TABLE'",
            [$dbName]
        );

        foreach ($tables as $table) {
            $tableName = $table->table_name ?? $table->TABLE_NAME;
            $this->line("  Dropping [{$tableName}] on [{$connection}]");
            Schema::connection($connection)->dropIfExists($tableName);
        }

        $db->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}