<?php

namespace App\Commands;

use App\Modules\Console\Output;
use App\Modules\Downloader\ImageFetcher;
use LaravelZero\Framework\Commands\Command;
use Throwable;

class ImgFetchCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'img:fetch
                                {storage? : Downloaded images output location}
                                {--s|source= : The source file path containing URLs or URL}
                                {--d|debug  : Throw exception on error}
                           ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Download images from saved URLs and save to storage';

    /**
     * @return void
     * @throws Throwable
     */
    public function handle() : void
    {
        if ($this->hasOption('source') && $this->option('source')) {
            $this->call('import', [
                'source'  => $this->option('source'),
                '--debug' => $this->option('debug'),
            ]);
        }

        $handler = new ImageFetcher(new Output(
            $this->output
        ));

        if ($this->option('debug')) {
            $handler->debug();
        }

        if ($this->hasArgument('storage') && $this->argument('storage')) {
            $handler->storage($this->argument(
                'storage'
            ));
        }

        $handler->handle();
    }
}
