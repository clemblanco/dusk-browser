# Laravel Dusk Browser

<p align="center">
<a href="https://packagist.org/packages/clemblanco/dusk-browser"><img src="https://poser.pugx.org/laravel/dusk/license.svg" alt="License"></a>
</p>

## Introduction

Laravel Dusk Browser provides an expressive, easy-to-use browsing API based on [Laravel Dusk 5.0](https://github.com/laravel/dusk/tree/5.0).

This is mainly for people who are only after the browsing capabilities of Laravel Dusk and want to use the same API to browse some remote websites.

Laravel Dusk can be un-secure to run on a production application due to the fact it's tightly coupled to PHPUnit and User Authentication, so this is a lighter version of Laravel Dusk which doesn't interact with any of this.

## Usage

```php
DuskBrowser::browse(function ($browser) {
    $altTextLogo = $browser->visit('https://www.google.com')
        ->attribute('#hplogo', 'alt');
});
```

## Installation 

```composer require dusk-browser/dusk-browser```

Laravel Dusk Browser comes with two commands, identical to Laravel Dusk: `dusk-browser:install` and `dusk-browser:chrome-driver`.

After installing the package, run the `dusk-browser:install` Artisan command:

```php artisan dusk-browser:install```

A `dusk-browser` directory will be created within your `storage/app` directory. Similar to Laravel Dusk, some screenshots and console logs of failed browsing sessions will be captured there.

For managing ChromeDriver installations, you can refer to the official [Laravel Dusk documentation](https://laravel.com/docs/5.8/dusk#managing-chromedriver-installations).

## Documentation

Because Laravel Dusk Browser is based on Laravel Dusk, they share quite a lot in common.

Based on [Laravel Dusk documentation](https://laravel.com/docs/5.8/dusk), this is what Laravel Dusk Browser doesn't support:

- Generating Tests
- Running Tests
- Environment Handling
- Authentication
- Database Migrations
- Making any sort of assertion
- Using Page and Component classes with shorthand selectors
- Components

Similar to Laravel Dusk, Laravel Dusk Browser comes with Google Chrome browser and Google ChromeDriver integration out of the box.

However, you are still free to use any other browser/driver combination by overriding the default invokable class used to resolve the driver to use.

To do so, you will need to publish the config file of the package using

```php artisan vendor:publish --provider="DuskBrowser\ServiceProvider"```

and specify your own `remote_web_driver` class name.

Feel free to copy the default class `DuskBrowser\ChromeRemoteWebDriver` over to your own application and simply modify/rename it to your needs, as long as it implements `DuskBrowser\Contracts\RemoteWebDriverContract`. For a Selenium driver using a phantomjs browser you might see something like:

```php
/**
 * Create the RemoteWebDriver instance.
 *
 * @return \Facebook\WebDriver\Remote\RemoteWebDriver
 */
public function __invoke()
{
    return RemoteWebDriver::create(
        config('dusk-browser.remote_web_driver_url'), DesiredCapabilities::phantomjs()
    );
}
```
We recommend you to keep using the `config()` helper here as this is used in order to do some additional checks on which driver is instantiated.
Depending on your web driver (Selenium, ChromeDriver...) `remote_web_driver_url` could change too.

## License

Laravel Dusk Browser is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
