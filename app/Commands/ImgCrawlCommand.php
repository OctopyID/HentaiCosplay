<?php

namespace App\Commands;

use App\Modules\Crawler\Crawler;
use App\Modules\Console\Output;
use Exception;
use LaravelZero\Framework\Commands\Command;
use Throwable;

class ImgCrawlCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'img:crawl
                                {source? : The source file path containing URLs or URL}
                                {--debug : Throw exception on error}
                           ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Crawl images source from file contains URL or directly';

    /**
     * Execute the console command.
     *
     * @return void require nunomaduro/laravel-desktop-notifier
     * @throws Throwable
     */
    public function handle() : void
    {
        $source = $this->argument('source');

        if (! $source) {
            $source = $this->ask('ENTER SOURCE FILE PATH OR URL');
        }

        $handler = new Crawler(new Output(
            $this->output
        ));

        if ($this->option('debug')) {
            $handler->debug();
        }

        $handler->handle(trim(
            $source
        ));
    }
}
