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

namespace CORS\Bundle\WebCareBundle\OpenApi\Config;

use Pimcore\Bundle\StudioBackendBundle\Controller\AbstractApiController;

/**
 * @internal
 */
final class Prefix
{
    public const string BUNDLE = AbstractApiController::PREFIX . '/bundle/web-care';
}
