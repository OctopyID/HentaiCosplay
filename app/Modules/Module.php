<?php

namespace App\Modules;

use App\Http\HttpClient;
use App\Modules\Console\Output;

abstract class Module
{
    /**
     * @var bool
     */
    protected bool $debug = false;

    /**
     * @var HttpClient
     */
    protected HttpClient $http;

    /**
     * @param  Output $output
     */
    public function __construct(protected Output $output)
    {
        $this->http = new HttpClient;
    }

    /**
     * @return void
     */
    public function debug() : void
    {
        $this->debug = true;
    }
}
