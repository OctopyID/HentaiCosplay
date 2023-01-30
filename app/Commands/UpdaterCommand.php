<?php

namespace App\Commands;

use App\Modules\Console\Output;
use App\Modules\Updater\Updater;

class UpdaterCommand extends \LaravelZero\Framework\Commands\Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Update the application to the latest version.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $updater = new Updater(new Output(
            $this->output
        ));

        if ($updater->check('v1.0.0')) {
            $updater->handle();
        } else {
            $this->components->info('NO NEW VERSION FOUND');
        }
    }
}
