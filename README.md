# Classnames for PHP

[![Build Status](https://img.shields.io/github/workflow/status/spatie/enum/run-tests?label=tests&style=flat-square)](https://github.com/spatie/enum/actions?query=workflow%3Arun-tests)

This package replicates the functionality of the popular JS package [JedWatson/classnames](https://github.com/JedWatson/classnames), allowing you to easily build HTML classlists using strings, stringable objects and arrays with conditions, like this:

```php
Classnames::from(
    'btn',
    ['btn-primary'],
    ['btn-secondary' => false],
    ['btn-wide' => true],
    new StringableObject('btn-lg')
);
// => 'btn btn-primary btn-wide btn-lg'
```

## Installation

You can install the package via composer:

```bash
composer require pangora/classnames
```

## Usage

Classnames accepts multiple arguments through the static `from` method and it'll respect the argument order when building the classlist. The `from` method won't remove duplicates, but you may use the static `dedupeFrom`, which works the same way, while also removing duplicates.

The allowed argument types are:

-   `string`
-   `int` (will be converted to `string`)
-   Any "stringable" object implementing the magic `__toString()` method.
-   Sequential arrays
-   Associative arrays where the key represents the value and the value represent the condition.

Arguments of other types will be ignored, except for multidimensional array which will throw an exception.

```php
Classnames::from('btn btn-primary');
// => 'btn btn-primary'

Classnames::from('btn', 'btn-primary');
// => 'btn btn-primary'

Classnames::from('   lost of ', ' space  ');
// => 'lots of space'

Classnames::from('btn', ['btn-primary']);
// => 'btn btn-primary'

Classnames::from([
    'btn' => false,
    'btn-secondary' => false,
    'btn-primary' => true,
]);
// => 'btn btn-primary'

Classnames::from(
    'card',
    new StringableObject('card-lg')
);
// => 'card card-lg'
```

You may also deduplicate the classlist using `dedupefrom`:

```php
Classnames::dedupeFrom('a btn b btn c');
// => 'a btn b c'
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jarand@pangora.no instead of using the issue tracker.

## Credits

-   [Jarand K. Løkeland](https://github.com/pangora)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
