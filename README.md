# Laravel Dusk Scraper

<p align="center">
<a href="https://packagist.org/packages/laravel/dusk"><img src="https://poser.pugx.org/laravel/dusk/license.svg" alt="License"></a>
</p>

## Introduction

Laravel Dusk Scraper provides an expressive, easy-to-use browser scraping API. Based on Laravel Dusk, both do not require you to install JDK or Selenium on your machine. Instead, both use a standalone Chromedriver. However, you are free to utilize any other Selenium driver you wish.

## Usage

```php
DuskScraper::browse(function ($browser) {
    $altTextLogo = $browser->visit('https://www.google.com')
        ->attribute('#hplogo', 'alt');
});
```

## Documentation

@todo

## License

Laravel Dusk Scraper is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
