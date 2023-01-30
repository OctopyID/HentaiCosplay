<?php

namespace App\Modules\Downloader;

use App\Models\Image;
use App\Modules\Console\Output;
use App\Modules\Module;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImageFetcher extends Module
{
    /**
     * @var string|null
     */
    protected string|null $storage = null;

    /**
     * @param  string $storage
     * @return void
     */
    public function storage(string $storage) : void
    {
        $this->storage = trim($storage);
    }

    /**
     * @return mixed
     * @throws Throwable
     */
    public function handle() : mixed
    {
        $images = Image::whereStatus(false)->get();

        if (! $images->count()) {
            return $this->output->info('NO IMAGE TO DOWNLOAD');
        }

        $this->output->info('STORAGE : ' . ($this->storage ?? Storage::disk('image')->path('')));

        return $images->each(function (Image $image) : void {

            $name = $this->name($image);

            $this->output->task($name, function () use ($name, $image) {
                if (! $this->storage) {
                    Storage::disk('image')->put($name, $this->http->fetch(
                        $image->url
                    ));
                } else {
                    $directory = preg_replace('/\/+/', '/', $this->storage . '/' . dirname($name));

                    if (! is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    file_put_contents($this->storage . '/' . $name, $this->http->fetch(
                        $image->url
                    ));
                }

                $image->update([
                    'status' => true,
                ]);
            });
        });
    }

    /**
     * @param  Image $image
     * @return string
     */
    private function name(Image $image) : string
    {
        $name = basename(parse_url(
            $image->page->main->url, PHP_URL_PATH
        ));

        if (strlen($name) > 100) {
            $name = substr($name, 0, 10);
        }

        $name .= '/';
        $name .= basename(parse_url(
            $image->url, PHP_URL_PATH
        ));

        return $name;
    }
}
