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
 * @license    https://opensource.org/license/mit MIT
 */

namespace CORS\Bundle\WebCareBundle\Entity;

class WebCareSite
{
    /**
     * @var int|null
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
        if ($this->id === null) {
            throw new \LogicException('WebCareSite has no id before it is persisted');
        }

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
