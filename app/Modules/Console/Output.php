<?php

namespace App\Modules\Console;

use Closure;
use Illuminate\Console\OutputStyle;
use Illuminate\Console\View\Components\Error;
use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class Output
{
    /**
     * @param  OutputStyle $output
     */
    public function __construct(protected OutputStyle $output)
    {
        //
    }

    /**
     * @param  int $max
     * @return ProgressBar
     */
    public function progress(int $max) : ProgressBar
    {
        return $this->output->createProgressBar($max);
    }

    /**
     * @param  string $string
     * @return true
     */
    public function info(string $string) : bool
    {
        (new Info($this->output))->render($string);

        return true;
    }

    /**
     * @param  string $string
     * @return bool
     */
    public function error(string $string) : bool
    {
        (new Error($this->output))->render($string);

        return true;
    }

    /**
     * @param  string  $name
     * @param  Closure $callback
     * @return void
     * @throws Throwable
     */
    public function task(string $name, Closure $callback) : void
    {
        if (strlen($name) > 120) {
            $name = substr($name, 0, 120);
        }

        (new Task($this->output))->render($name, $callback);
    }
}
