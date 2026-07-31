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
    private const string STUDIO_BACKEND_BUNDLE = 'PimcoreStudioBackendBundle';

    private const string STUDIO_UI_BUNDLE = 'PimcoreStudioUiBundle';

    private const string STUDIO_BACKEND_EXTENSION = 'pimcore_studio_backend';

    /**
     * Prefix of the Studio API routes of this bundle. Kept as an unresolved parameter placeholder,
     * so the StudioBackendBundle stays the single source of truth for its url prefix.
     */
    private const string STUDIO_ROUTE_PREFIX = '%pimcore_studio_backend.url_prefix%/bundle/web-care';

    public function prepend(ContainerBuilder $container): void
    {
        // The Studio API controllers and schemas of this bundle have to be scanned separately,
        // the StudioBackendBundle only scans its own src directory by default.
        if ($container->hasExtension(self::STUDIO_BACKEND_EXTENSION)) {
            $container->prependExtensionConfig(
                self::STUDIO_BACKEND_EXTENSION,
                [
                    'open_api_scan_paths' => [
                        dirname(__DIR__),
                    ],
                ],
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configs = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $studioBackend = $this->hasBundle($container, self::STUDIO_BACKEND_BUNDLE);

        // Consumed by the StudioRouteLoader: a null prefix disables the Studio routes entirely.
        $container->setParameter(
            'cors_web_care.studio.route_prefix',
            $studioBackend ? self::STUDIO_ROUTE_PREFIX : null,
        );

        $loader->load('services.yaml');

        if ($studioBackend) {
            $loader->load('services/studio.yaml');
        }

        if ($this->hasBundle($container, self::STUDIO_UI_BUNDLE)) {
            $loader->load('services/studio_ui.yaml');
        }
    }

    /**
     * Symfony hands load() a temporary container that carries no extensions, so hasExtension()
     * is not usable there. The parameter bag however is the real one, and kernel.bundles is
     * populated by the kernel before any extension is loaded.
     */
    private function hasBundle(ContainerBuilder $container, string $bundle): bool
    {
        /** @var array<string, class-string> $bundles */
        $bundles = $container->hasParameter('kernel.bundles') ? $container->getParameter('kernel.bundles') : [];

        return isset($bundles[$bundle]);
    }
}
