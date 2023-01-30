<?php

namespace App\Commands;

use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Warn;
use LaravelZero\Framework\Commands\Command;

class AppInitCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Initialize the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        if (! is_dir(home_path(''))) {
            mkdir(home_path(''), 0755, true);
        }

        if (is_file(home_path('database.sqlite'))) {
            $this->components->warn('DATABASE ALREADY EXISTS');
        } else {

            config([
                'database.connections.sqlite.database' => home_path('database.sqlite'),
            ]);

            $this->callSilently('migrate', [
                '--force' => true,
            ]);

            $this->components->info('DATABASE INITIALIZED');

            $this->notify('DATABASE INITIALIZED', 'The database has been initialized successfully.');
        }
    }
}
