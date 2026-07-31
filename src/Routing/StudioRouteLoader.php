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

namespace CORS\Bundle\WebCareBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Loads the Studio API routes of this bundle, but only if the Studio backend is installed.
 *
 * Routing files cannot be loaded conditionally, and the Studio controllers extend classes of
 * the StudioBackendBundle - importing them without Studio would fatal. Therefore the routes are
 * imported through this loader, which the extension disables (by passing a null prefix) whenever
 * the StudioBackendBundle is not registered.
 *
 * @internal
 */
final class StudioRouteLoader extends Loader
{
    public const string TYPE = 'cors_web_care_studio';

    public function __construct(private readonly ?string $routePrefix)
    {
        parent::__construct();
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if (null === $this->routePrefix) {
            return new RouteCollection();
        }

        /** @var RouteCollection $collection */
        $collection = $this->import('@CORSWebCareBundle/Controller/Studio', 'attribute');
        $collection->addPrefix($this->routePrefix);
        $collection->addOptions(['expose' => true]);

        return $collection;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return self::TYPE === $type;
    }
}
