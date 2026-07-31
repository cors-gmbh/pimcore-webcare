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

namespace CORS\Bundle\WebCareBundle\Attribute\Request;

use Attribute;
use CORS\Bundle\WebCareBundle\MappedParameter\CreateWebCareSiteParameters;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;

#[Attribute(Attribute::TARGET_METHOD)]
final class CreateWebCareSiteRequestBody extends RequestBody
{
    public function __construct()
    {
        parent::__construct(
            required: true,
            content: new JsonContent(ref: CreateWebCareSiteParameters::class)
        );
    }
}
