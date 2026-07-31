<?php

declare(strict_types=1);

/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
 */

namespace CORS\Bundle\WebCareBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class CORSWebCareExtension extends Extension implements PrependExtensionInterface
{
    private const string STUDIO_BACKEND_EXTENSION = 'pimcore_studio_backend';

    public function prepend(ContainerBuilder $container): void
    {
        // The Studio API controllers and schemas of this bundle have to be scanned separately,
        // the StudioBackendBundle only scans its own src directory by default.
        $container->prependExtensionConfig(
            self::STUDIO_BACKEND_EXTENSION,
            [
                'open_api_scan_paths' => [
                    dirname(__DIR__),
                ],
            ],
        );
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configs = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');
        $loader->load('services/studio.yaml');
    }
}
