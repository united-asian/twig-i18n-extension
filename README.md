UAM Twig I18n Extension
=======================

The `uam/twig-i18n-extension` package provides a Twig extension for internationalizing dates, numbers, etc.

Installation
------------

Add the package to your project's `composer.json`:

```json
{
    "require": {
        "uam/twig-i18n-extension": "dev-master",
        ...
    }
}
```

Run `composer install` or `composer update` to install the package:

``` bash
$ php composer.phar update
```

Usage
-----
Add the extension to the Twig_Environment:

``` php
use UAM\Twig\Extension\I18n\I18nExtension;

$twig = new Twig_Environment(...);

$twig->addExtension(new I18nExtension());
```


Symfony2
--------

To use the extension in a symfony2 app, use the built-in `UAMTwigI18nBundle`:

Enable the bundle in your app's Appkernel:

``` php
# app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new UAM\Twig\Extension\I18n\Bridge\Symfony\UAMTwigI18nBundle(),
            ...
        );
```

The bundle will automaticallty register the `UAM\Twig\Extension\I18nExtension` as a twig extension.