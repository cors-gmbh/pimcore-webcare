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

namespace CORS\Bundle\WebCareBundle\Entity;

class WebCareSite
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var int|null
     */
    protected $siteId;

    /**
     * @var string|null
     */
    protected $clientId;

    /**
     * @var string|null
     */
    protected $organizationId;

    /**
     * @var string|null
     */
    protected $websiteId;

    public function getId(): int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    public function setSiteId(?int $siteId): void
    {
        $this->siteId = $siteId;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function setOrganizationId(?string $organizationId): void
    {
        $this->organizationId = $organizationId;
    }

    public function getWebsiteId(): ?string
    {
        return $this->websiteId;
    }

    public function setWebsiteId(?string $websiteId): void
    {
        $this->websiteId = $websiteId;
    }
}
