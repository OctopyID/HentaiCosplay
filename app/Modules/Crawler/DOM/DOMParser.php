<?php

namespace App\Modules\Crawler\DOM;

use Illuminate\Support\Collection;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;

class DOMParser
{
    /**
     * @var Dom
     */
    protected Dom $parser;

    /**
     * @param  string $html
     */
    public function __construct(protected string $html)
    {
        $this->parser = new Dom;
    }

    /**
     * @return string
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     * @throws NotLoadedException
     */
    public function title() : string
    {
        return $this->dom()->find('#title > h2')[0]->text;
    }

    /**
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws StrictException
     * @throws LogicalException
     */
    public function pages(string $url = null) : Collection
    {
        $pages = $this->dom()->find('#post')[0]->find('#paginator')[0]->find('span');

        # 4 is first, next, prev & last
        $pages = collect(range(1, $pages->count() - 4))->map(function ($page) {
            return 'page/' . $page . '/';
        });

        if ($url) {
            return $pages->map(function (string $page) use ($url) {
                return $url . $page;
            });
        }

        return $pages;
    }

    /**
     * @return Collection
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function images() : Collection
    {
        $images = $this->dom()->find('#post')[0]->find('#display_image_detail')[0]->find('.icon-overlay')->toArray();

        return collect($images)->map(function ($image) {
            return $image->find('img')[0]->getAttribute('src');
        });
    }

    /**
     * @throws ChildNotFoundException
     * @throws ContentLengthException
     * @throws CircularException
     * @throws LogicalException
     * @throws StrictException
     */
    private function dom() : Dom
    {
        return $this->parser->loadStr($this->html);
    }
}
