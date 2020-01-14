# JsonApi-Structure

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Infection MSI][ico-mutation]][link-mutation]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practices by being named the following.

```
bin/        
build/
docs/
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require vgirol/jsonapi-structure
```

## Usage

``` php
$skeleton = new VGirol\JsonApi-Structure();
echo $skeleton->echoPhrase('Hello, League!');
```

## Documentation

The API documentation is available in XHTML format at the url [http://JsonApi-Structure.girol.fr/docs/index.xhtml](http://JsonApi-Structure.girol.fr/docs/index.xhtml).

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
[ico-mutation]: https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Structure/master
[ico-downloads]: https://img.shields.io/packagist/dt/VGirol/JsonApi-Structure.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/VGirol/JsonApi-Structure
[link-travis]: https://travis-ci.org/VGirol/JsonApi-Structure
[link-scrutinizer]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Structure/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Structure
[link-downloads]: https://packagist.org/packages/VGirol/JsonApi-Structure
[link-author]: https://github.com/VGirol
[link-mutation]: https://infection.github.io
[link-contributors]: ../../contributors
