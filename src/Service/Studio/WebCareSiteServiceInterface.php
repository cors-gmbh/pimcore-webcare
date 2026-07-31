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

namespace CORS\Bundle\WebCareBundle\Service\Studio;

use CORS\Bundle\WebCareBundle\MappedParameter\CreateWebCareSiteParameters;
use CORS\Bundle\WebCareBundle\MappedParameter\UpdateWebCareSiteParameters;
use CORS\Bundle\WebCareBundle\Schema\WebCareSite;
use Exception;
use Pimcore\Bundle\StudioBackendBundle\Exception\Api\ConflictException;
use Pimcore\Bundle\StudioBackendBundle\Exception\Api\NotFoundException;

/**
 * @internal
 */
interface WebCareSiteServiceInterface
{
    /**
     * Lists every Pimcore site (plus the main site as id 0) together with its WebCare
     * configuration. Sites without a configuration are returned as placeholder rows
     * with a `null` id.
     *
     * @return array<int, WebCareSite>
     *
     * @throws Exception
     */
    public function listWebCareSites(): array;

    /**
     * @throws ConflictException
     * @throws Exception
     */
    public function createWebCareSite(CreateWebCareSiteParameters $parameters): WebCareSite;

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function updateWebCareSite(int $id, UpdateWebCareSiteParameters $parameters): WebCareSite;

    /**
     * @throws NotFoundException
     */
    public function deleteWebCareSite(int $id): void;
}
