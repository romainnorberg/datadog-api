<p align="center">
    <p align="center">
        <strong>DO NOT USE YET, PACKAGE IN DEVELOPMENT. NON OFFICIAL PACKAGE</strong>
    </p>
    <p align="center">
        <img src=".github/media/logo-horizontal.png?raw=true" width="600" alt="Datadog API">
    </p>
    <p class="rich-diff-level-zero" align="center">
        <a href="https://packagist.org/packages/romainnorberg/datadog-api" rel="nofollow" class="rich-diff-level-one"><img src="https://img.shields.io/packagist/v/romainnorberg/datadog-api.svg?style=flat-square" alt="Latest Version on Packagist" style="max-width:100%;"></a>
        <a href="https://github.com/romainnorberg/datadog-api/actions?query=workflow%3Arun-tests+branch%3Amaster" class="rich-diff-level-one"><img src="https://img.shields.io/github/workflow/status/romainnorberg/datadog-api/run-tests?label=tests" alt="GitHub Tests Action Status" style="max-width:100%;"></a>
    <a href="https://codecov.io/gh/romainnorberg/datadog-api" rel="nofollow" class="rich-diff-level-one"><img src="https://codecov.io/gh/romainnorberg/datadog-api/branch/master/graph/badge.svg" alt="codecov" style="max-width:100%;"></a>
    <a href="https://packagist.org/packages/romainnorberg/datadog-api/stats" rel="nofollow" class="rich-diff-level-one"><img src="https://img.shields.io/packagist/dt/romainnorberg/datadog-api.svg?style=flat-square" alt="Total Downloads" style="max-width:100%;"></a></p>
    <p align="center">
        The missing DataDog API PHP Wrapper! A lightweight package to fetch metrics from Datadog
    </p>
</p>

***

## Actual scope
- Metrics  [doc](https://docs.datadoghq.com/api/v1/metrics/)
    - ✅ Get active list
    - ✅ Get metadata
    - ✅ Query timeseries points
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

### Get max values for a specific period

|----^-------|

Using `$metrics->maxPoint()` provide the highest point for the requested period.

```php
use Romainnorberg\DataDogApi\DataDogApi;

$datadogApi = new DataDogApi(); // Using env 

$metrics = $datadogApi
            ...
            ->query('sum:nginx.net.request_per_s{*}')
            ->handle();

$max = $metrics->maxPoint();
/**
* Romainnorberg\DataDogApi\Model\Metrics\Point {
    +timestamp: 1588269614000
    +value: 68.389493934967
  }
 */
```

Using `$metrics->maxPointBySerie()` provide highest points by serie for the requested period. By example if you query by host, you can retrieve max point by host.

```php
use Romainnorberg\DataDogApi\DataDogApi;

$datadogApi = new DataDogApi(); // Using env 

$metrics = $datadogApi
            ...
            ->query('sum:nginx.net.request_per_s{*} by{host}')
            ->handle();

$maxBySerie = $metrics->maxPointBySerie();

/*
 * array:4 [
     0 => array:2 [
       "serie" => Romainnorberg\DataDogApi\Model\Metrics\Serie^ {#292
         +aggr: "sum"
         +end: 1588269659000
         +expression: "sum:nginx.net.request_per_s{host:staging}"
         +interval: 1
         +length: 32
         +metric: "nginx.net.request_per_s"
         +pointlist: []
         +scope: "host:staging"
         +start: 1588269548000
       }
       "point" => array:2 [
         0 => 1588269594000.0
         1 => 1.1333215343856
       ]
     ]
     1 => array:2 [
       "serie" => Romainnorberg\DataDogApi\Model\Metrics\Serie^ {#70
         +aggr: "sum"
         +end: 1588269659000
         +expression: "sum:nginx.net.request_per_s{host:frontend1}"
         +interval: 1
         +length: 32
         +metric: "nginx.net.request_per_s"
         +pointlist: []
         +scope: "host:frontend1"
         +start: 1588269548000
       }
       "point" => array:2 [
         0 => 1588269608000.0
         1 => 51.069667634348
       ]
     ]
     ...
 */
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

The MIT License (MIT). Please see [License File](LICENSE) for more information.
