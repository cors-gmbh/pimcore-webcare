<?php

declare(strict_types=1);

/*
 * CORS GmbH
 *
 * This source file is available under the MIT license
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license MIT
 */

namespace CORS\Bundle\WebCareBundle\Hydrator;

use CORS\Bundle\WebCareBundle\Entity\WebCareSite as WebCareSiteEntity;
use CORS\Bundle\WebCareBundle\Schema\WebCareSite;

/**
 * @internal
 */
final readonly class WebCareSiteHydrator implements WebCareSiteHydratorInterface
{
    public function hydrateWebCareSite(WebCareSiteEntity $entity, string $siteDomain): WebCareSite
    {
        return new WebCareSite(
            $entity->getId(),
            $entity->getSiteId() ?? 0,
            $siteDomain,
            $entity->isActive(),
            $entity->getClientId(),
            $entity->getOrganizationId(),
            $entity->getWebsiteId(),
        );
    }

    public function hydrateUnconfiguredSite(int $siteId, string $siteDomain): WebCareSite
    {
        return new WebCareSite(
            null,
            $siteId,
            $siteDomain,
            false,
            null,
            null,
            null,
        );
    }
}
