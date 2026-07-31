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

namespace CORS\Bundle\WebCareBundle\Schema;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Pimcore\Bundle\StudioBackendBundle\Util\Schema\AdditionalAttributesInterface;
use Pimcore\Bundle\StudioBackendBundle\Util\Trait\AdditionalAttributesTrait;

/**
 * @internal
 */
#[Schema(
    schema: 'BundleWebCareSite',
    title: 'Bundle WebCare Site',
    required: ['id', 'siteId', 'siteDomain', 'active'],
    type: 'object',
)]
final class WebCareSite implements AdditionalAttributesInterface
{
    use AdditionalAttributesTrait;

    public function __construct(
        #[Property(
            description: 'WebCare configuration id, null when the site has no configuration yet',
            type: 'integer',
            example: 1,
            nullable: true,
        )]
        private readonly ?int $id,
        #[Property(description: 'Pimcore site id, 0 for the main site', type: 'integer', example: 0)]
        private readonly int $siteId,
        #[Property(description: 'Main domain of the site', type: 'string', example: 'www.example.com')]
        private readonly string $siteDomain,
        #[Property(description: 'Whether the WebCare integration is active', type: 'boolean', example: true)]
        private readonly bool $active,
        #[Property(description: 'WebCare client id', type: 'string', example: null, nullable: true)]
        private readonly ?string $clientId,
        #[Property(description: 'WebCare organization id', type: 'string', example: null, nullable: true)]
        private readonly ?string $organizationId,
        #[Property(description: 'WebCare website id', type: 'string', example: null, nullable: true)]
        private readonly ?string $websiteId,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteId(): int
    {
        return $this->siteId;
    }

    public function getSiteDomain(): string
    {
        return $this->siteDomain;
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
