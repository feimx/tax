# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/feimx/Tax.svg?style=flat-square)](https://packagist.org/packages/feimx/Tax)
[![Build Status](https://img.shields.io/travis/feimx/Tax/master.svg?style=flat-square)](https://travis-ci.org/feimx/Tax)
[![Quality Score](https://img.shields.io/scrutinizer/g/feimx/Tax.svg?style=flat-square)](https://scrutinizer-ci.com/g/feimx/Tax)
[![Total Downloads](https://img.shields.io/packagist/dt/feimx/Tax.svg?style=flat-square)](https://packagist.org/packages/feimx/Tax)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require feimx/Tax
```

## Usage

``` php
$taxManager = new FeiMx\Tax\TaxManager($amount = 100);
$taxManager->addTax('iva');
echo $taxManager->get(); // 116.000000
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
