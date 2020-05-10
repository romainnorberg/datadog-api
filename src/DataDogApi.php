<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi;

use Romainnorberg\DataDogApi\Exceptions\InvalidCredentials;
use Romainnorberg\DataDogApi\Services\Timeserie;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class DataDogApi
{
    private ContainerBuilder $containerBuilder;
    private ?string $apiKey;
    private ?string $applicationKey;

    public function __construct(
        ?string $apiKey = null,
        ?string $applicationKey = null
    ) {
        $this->apiKey = $apiKey;
        $this->applicationKey = $applicationKey;

        $this->assertCredentials();
        $this->setContainer();
    }

    // health

    // rate limiting

    public function timeserie(): Timeserie
    {
        return $this->containerBuilder->get(Timeserie::class);
    }

    private function assertCredentials()
    {
        // try to load credentials from env
        if (null === $this->apiKey || null === $this->applicationKey) {
            $this->loadCredentialFromEnv();
        }

        if (null === $this->apiKey || null === $this->applicationKey) {
            throw InvalidCredentials::credentialsAreMissing();
        }
    }

    private function setContainer(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('../config/services.yaml');

        $this->containerBuilder->setParameter('credential.api_key', $this->apiKey);
        $this->containerBuilder->setParameter('credential.application_key', $this->applicationKey);

        $this->containerBuilder->compile();
    }

    private function loadCredentialFromEnv(): void
    {
        $this->apiKey = $_SERVER['DATADOG_API_KEY'] ?? null;
        $this->applicationKey = $_SERVER['DATADOG_APPLICATION_KEY'] ?? null;
    }
}
