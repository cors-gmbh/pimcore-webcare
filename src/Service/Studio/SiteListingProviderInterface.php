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
