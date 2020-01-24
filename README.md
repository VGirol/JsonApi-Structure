# JsonApi-Structure

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Infection MSI][ico-mutation]][link-mutation]
[![Total Downloads][ico-downloads]][link-downloads]

This package provides a set of tools to check content of a request using the [JSON:API specification](https://jsonapi.org/).

## Technologies

- PHP 7.2+

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-structure": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

``` bash
$ composer require vgirol/jsonapi-structure
```

## Usage

``` php
use VGirol\JsonApiStructure\ValidateService;

$json = [
    'data' => [
        'type' => 'resource',
        'id' => '5'
    ],
    'jsonapi' => [
        'version' => '1.0',
        'meta' => [
            'key' => 'value'
        ]
    ]
];

$service = new ValidateService('POST');
$service->validateStructure($json);
```

## Documentation

The API documentation is available in XHTML format at the url [http://jsonapi-structure.girol.fr/docs/index.xhtml](http://jsonapi-structure.girol.fr/docs/index.xhtml).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [vincent@girol.fr](mailto:vincent@girol.fr) instead of using the issue tracker.

## Credits

- [Vincent Girol][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/VGirol/JsonApi-Structure.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/VGirol/JsonApi-Structure/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/VGirol/JsonApi-Structure.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/VGirol/JsonApi-Structure.svg?style=flat-square
[ico-mutation]: https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FVGirol%2FJsonApi-Structure%2Fmaster
[ico-downloads]: https://img.shields.io/packagist/dt/VGirol/JsonApi-Structure.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/VGirol/JsonApi-Structure
[link-travis]: https://travis-ci.org/VGirol/JsonApi-Structure
[link-scrutinizer]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Structure/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Structure
[link-downloads]: https://packagist.org/packages/VGirol/JsonApi-Structure
[link-author]: https://github.com/VGirol
[link-mutation]: https://dashboard.stryker-mutator.io/reports/github.com/VGirol/JsonApi-Structure/master
[link-contributors]: ../../contributors
