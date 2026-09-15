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

namespace CORS\Bundle\WebCareBundle\Service\Studio;

use Pimcore\Model\Site;

/**
 * @internal
 */
final readonly class SiteListingProvider implements SiteListingProviderInterface
{
    public function getSites(): array
    {
        return (new Site\Listing())->getSites();
    }
}
