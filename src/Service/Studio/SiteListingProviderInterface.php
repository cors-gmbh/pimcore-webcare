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

use Exception;
use Pimcore\Model\Site;

/**
 * Thin wrapper around Pimcore's site listing so the Studio service stays testable.
 *
 * @internal
 */
interface SiteListingProviderInterface
{
    /**
     * @return array<int, Site>
     *
     * @throws Exception
     */
    public function getSites(): array;
}
