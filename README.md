**DO NOT USE YET, PACKAGE IN DEVELOPMENT**

# DataDog-api

[![Latest Version on Packagist](https://img.shields.io/packagist/v/romainnorberg/datadog-api.svg?style=flat-square)](https://packagist.org/packages/romainnorberg/datadog-api)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/romainnorberg/datadog-api/run-tests?label=tests)](https://github.com/romainnorberg/datadog-api/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/romainnorberg/datadog-api.svg?style=flat-square)](https://packagist.org/packages/romainnorberg/datadog-api/stats)

A lightweight packages to fetch metrics from datadog

## Scope
- Metrics  [doc](https://docs.datadoghq.com/api/v1/metrics/)
    - Get active list
    - Get metadata
    - Query timeseries points
    - ~~Edit metadata~~
    - ~~Search~~
    - ~~Submit metrics~~

## Installation

You can install the package via composer:

```bash
composer require romainnorberg/datadog-api
```

## Usage / examples

### Instantiation

```php
use Romainnorberg\DataDogApi\DataDogApi;

// Using env (DATADOG_API_KEY & DATADOG_APPLICATION_KEY)
$datadogApi = new DataDogApi();  

// Using arguments
$datadogApi = new DataDogApi('<apiKey>', '<applicationKey>');
```

### Fetching metrics

```php
use Romainnorberg\DataDogApi\DataDogApi;

$datadogApi = new DataDogApi(); // Using env 

$metrics = $datadogApi
            ->metrics()
            ->query()
            ->from((new \DateTime())->sub(new \DateInterval('PT1H')))
            ->to(new \DateTime())
            ->query('avg:worker.memory.heapUsed{*}')
            ->handle();
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email romainnorberg@gmail.com instead of using the issue tracker.

## Credits

- [Romain Norberg](https://github.com/romainnorberg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
