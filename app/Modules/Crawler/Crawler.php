<?php

namespace App\Modules\Crawler;

use App\Http\HttpClient;
use App\Models\MainPage;
use App\Models\SubPage;
use App\Modules\Crawler\DOM\DOMParser;
use App\Modules\Console\Output;
use App\Modules\Module;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class Crawler extends Module
{
    /**
     * @param  string $source
     * @return void
     * @throws Throwable
     */
    public function handle(string $source) : void
    {
        // check if first string is `~` and replace with HOME from getenv
        if (Str::startsWith($source, '~')) {
            $source = Str::replaceFirst('~', getenv('HOME'), $source);
        }

        if ($this->isURL($source)) {
            if ($this->output->info('IMPORTING FROM LINK')) {
                $this->import(trim(
                    $source
                ));
            }
        } else if (is_file($source)) {
            // TODO: check if file is readable
            if ($this->output->info('IMPORTING FROM FILE')) {
                collect(file($source))->each(function ($link) {
                    $this->import(trim(
                        $link
                    ));
                });
            }
        } else {
            if (! $this->debug) {
                $this->output->error('INVALID SOURCE');
            } else {
                throw new Exception('Invalid Source');
            }
        }
    }

    /**
     * @param  string $url
     * @return void
     * @throws Throwable
     */
    protected function import(string $url) : void
    {
        $name = basename(parse_url(
            $url, PHP_URL_PATH
        ));

        $this->output->task($name, function () use ($url) {
            try {
                $main = MainPage::firstOrCreate(['hash' => md5($url)], [
                    'url' => $url,
                ]);

                // skip if already scraped
                if ($main->status) {
                    return true;
                }

                $content = new DOMParser($this->evaluate(
                    $main
                ));

                $main->update([
                    'title'  => $content->title(),
                    'status' => true,
                ]);

                $content
                    ->pages($url) // find all pages
                    ->map(function (string $page) use ($main) {
                        // create or update sub_pages
                        return $main->pages()->updateOrCreate(['hash' => md5($page)], [
                            'url' => $page,
                        ]);
                    })
                    // reject when sub_pages already scraped
                    ->reject(function (SubPage $page) {
                        return $page->status;
                    })
                    ->each(function (SubPage $page) {
                        $content = new DOMParser($this->evaluate(
                            $page
                        ));

                        // mark sub_page as scraped
                        $page->update([
                            'status' => true,
                        ]);

                        $content
                            ->images() // find all images
                            ->each(function (string $url) use ($page) {
                                $page->images()->updateOrCreate(['hash' => md5($url)], [
                                    'url' => $url,
                                ]);
                            });
                    });

                return true;
            } catch (Throwable $exception) {
                if ($this->debug) {
                    throw $exception;
                }

                return false;
            }
        });
    }

    /**
     * @param  MainPage|SubPage $page
     * @return string
     */
    protected function evaluate(MainPage|SubPage $page) : string
    {
        return Cache::rememberForever($page->hash, function () use ($page) {
            return $this->http->fetch($page->url)->body();
        });
    }

    /**
     * @param  string $source
     * @return bool
     */
    protected function isURL(string $source) : bool
    {
        return filter_var($source, FILTER_VALIDATE_URL);
    }
}
