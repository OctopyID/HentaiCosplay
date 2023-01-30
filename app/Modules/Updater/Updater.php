<?php

namespace App\Modules\Updater;

use App\Modules\Console\Output;
use App\Modules\Module;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Helper\ProgressBar;

class Updater extends Module
{
    /**
     * @var ProgressBar|null
     */
    protected ProgressBar|null $progress = null;

    /**
     * @param  string $version
     * @return bool
     */
    public function check(string $version) : bool
    {
        return version_compare($version, $this->release('tag_name')) === -1;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        $bar = $this->output->progress($this->release(
            'assets.0.size'
        ));

        $bar->start();

        $this->http->fetch($this->release('assets.0.browser_download_url'), [
            RequestOptions::PROGRESS => function ($total, $downloaded) use ($bar) {
                $bar->advance($downloaded);
            },
        ]);

        $bar->finish();
    }

    /**
     * @param  string $key
     * @return mixed
     */
    private function release(string $key) : mixed
    {
//        return Cache::remember('updatsdawderz', 60, function () : Response {
        return $this->http->fetch('https://api.github.com/repos/SupianIDz/HentaiCosplay/releases/latest')
//        })
            ->json($key);
    }
}
