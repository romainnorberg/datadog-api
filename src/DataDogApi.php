<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi;

use Romainnorberg\DataDogApi\Exceptions\InvalidCredentials;
use Romainnorberg\DataDogApi\Services\Metrics;
use Romainnorberg\DataDogApi\Services\Validate;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class DataDogApi
{
    private ContainerBuilder $container;
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

    public function validate(): bool
    {
        return $this->container->get(Validate::class)();
    }

    public function metrics(): Metrics
    {
        return $this->container->get(Metrics::class);
    }

    private function assertCredentials(): void
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
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__));
        $loader->load('../config/services.yaml');

        $this->container->setParameter('credential.api_key', $this->apiKey);
        $this->container->setParameter('credential.application_key', $this->applicationKey);

        $this->container->compile();
    }

    private function loadCredentialFromEnv(): void
    {
        $this->apiKey = $_SERVER['DATADOG_API_KEY'] ?? null;
        $this->applicationKey = $_SERVER['DATADOG_APPLICATION_KEY'] ?? null;
    }
}
