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

namespace CORS\Bundle\WebCareBundle\MappedParameter;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

/**
 * @internal
 */
#[Schema(
    schema: 'BundleWebCareCreateSiteParameters',
    title: 'Bundle WebCare Create Site Parameters',
    description: 'Parameters for creating a WebCare site configuration',
    type: 'object',
)]
final readonly class CreateWebCareSiteParameters
{
    public function __construct(
        #[Property(description: 'Pimcore site id, 0 for the main site', type: 'integer', example: 0)]
        private int $siteId = 0,
        #[Property(description: 'Whether the WebCare integration is active', type: 'boolean', example: true)]
        private bool $active = false,
        #[Property(description: 'WebCare client id', type: 'string', example: null, nullable: true)]
        private ?string $clientId = null,
        #[Property(description: 'WebCare organization id', type: 'string', example: null, nullable: true)]
        private ?string $organizationId = null,
        #[Property(description: 'WebCare website id', type: 'string', example: null, nullable: true)]
        private ?string $websiteId = null,
    ) {
    }

    public function getSiteId(): int
    {
        return $this->siteId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function getWebsiteId(): ?string
    {
        return $this->websiteId;
    }
}
