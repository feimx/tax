# Calculate taxes for a given amount

[![Latest Version on Packagist](https://img.shields.io/packagist/v/feimx/tax.svg?style=flat-square)](https://packagist.org/packages/feimx/tax)
[![Build Status](https://img.shields.io/travis/feimx/tax/master.svg?style=flat-square)](https://travis-ci.org/feimx/tax)
[![Quality Score](https://img.shields.io/scrutinizer/g/feimx/tax.svg?style=flat-square)](https://scrutinizer-ci.com/g/feimx/tax)
[![Total Downloads](https://img.shields.io/packagist/dt/feimx/tax.svg?style=flat-square)](https://packagist.org/packages/feimx/tax)

The `feimx/tax` package provide a simple way for calculate taxes of an amount.

## Basic Usage

``` php
$taxManager = new FeiMx\Tax\TaxManager($amount = 100);
$taxManager->addTax('iva');
echo $taxManager->total(); // 116.000000
```

## Installation

You can install the package via composer:

```bash
composer require feimx/tax
```

Next, you must install the service provider:

```php
// config/app.php
'providers' => [
    FeiMx\Tax\TaxServiceProvider::class,
];
```

_Note:_ If your laravel versions is `>=5.5` you don't need register providers.

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="FeiMx\Tax\TaxServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /**
     * Used The fallback type determines the type to use when the current one
     * is not available. You may change the value to correspond to any of
     * provided types
     */
    'fallback' => 'default',
    /**
     * List of taxes with their types ans percentages
     * You can add more types and percentages.
     */
    'taxes' => [
        'iva' => [
            'default' => 0.16,
            'retention' => -0.106667,
        ],
        'isr' => [
            'default' => -0.106667,
        ],
        'ieps' => [
            'default' => 0.08,
            'retention' => -0.08,
            'primary' => 0.11,
            'secondary' => 0.13,
        ],
    ],
];
```

## Usage

Firt need create a new instance of `TaxManager`:

``` php
$taxManager = new FeiMx\Tax\TaxManager($amount = 100);
```

Second you need to add the taxes for calculate the final amount:
The first parameter could be a tax name `['iva', 'ieps', 'isr']` or an instance of `FeiMx\Tax\Contracts\TaxContract`.
Exist 3 Tax Objects:

``` php
$iva = new \FeiMx\Tax\Taxes\IVA($retention = false);
$isr = new \FeiMx\Tax\Taxes\ISR($retention = false);
$ieps = new \FeiMx\Tax\Taxes\IEPS($retention = false);

$taxManager->addTax($tax = 'iva', $retention = false);
$taxManager->addTax($iva);
```

You can add multiple taxes at once:

``` php
$taxManager->addTaxes([
    'iva', $isr, $ieps,
]);
```

Now you can get the final amount:

``` php
$taxManager->total();
// or
$taxManager->total;
```

You can get a list for given data:

``` php
$taxManager->get();
```

This is the contents of get method:

``` php
[
    'amount' => 100,
    'total' => '105.333300',
    'taxes' => [
        [
            'tax' => 'iva',
            'amount' => '16.000000',
        ],
        [
            'tax' => 'isr',
            'amount' => '-10.666700',
        ],
    ],
];
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email yorch@fei.com.mx instead of using the issue tracker.


## Credits

- [Jorge Andrade](https://github.com/Yorchi)
- [All Contributors](../../contributors)

## Support us

FEI is a Digital Invoicing startup based in Yucatán, México. You'll find an overview of all our open source projects [on our website](https://fei.com.mx/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/jorge_andrade). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
