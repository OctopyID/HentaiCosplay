# Hentai Cosplay Downloader

This makes it easier for you to download files from [hentai-cosplays.com](https://hentai-cosplays.com/) or [hentai-img.com](https://hentai-img.com/) without having to do it one by one, and you don't have to remember your
download progress because we will take care of it.

> **Warning**
> 
> This package may contain pornographic content, by using this package you agree that we and the [contributors](https://github.com/SupianIDz/HentaiCosplay/graphs/contributors) will not be responsible for any problems caused by this application.

## Installation

Please make sure you have the following requirements installed below:

- SQLite3
- PHP 8.1 with curl, sqlite3, and mbstring extensions

Download the latest release from the [releases page](https://github.com/SupianIDz/HentaiCosplay/releases).

## Usage

First you need to initialize the database by running the following command.

```bash
php hentai app:init
```

### Crawl URLs

You need to crawl the URLs from the website. You can crawl a single URL using the following command.

```bash
php hentai img:crawl https://hentai-cosplays.com/image/coserpingping-misa-amane/
```

or you can crawl multiple URLs at once using a text file containing the URLs line by line.

```bash
php hentai img:crawl ./source.txt
```

### Getting Images

After crawling the URLs, you can download the images using the following command.

By default, the images will be downloaded to the `~/.hentai/images` directory.

```bash
php hentai img:fetch
```

or you can set the download directory by adding output directory as the second argument.

```bash
php hentai img:fetch ~/output/
```

## Credits

- [Supian M](https://github.com/SupianIDz)
- [Octopy ID](https://github.com/OctopyID)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
