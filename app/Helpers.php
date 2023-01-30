<?php

if (! function_exists('home_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function home_path(string $path = '') : string
    {
        return getenv('HOME') . '/.hentai/' . $path;
    }
}
