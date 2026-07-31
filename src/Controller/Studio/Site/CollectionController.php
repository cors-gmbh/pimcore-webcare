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

namespace CORS\Bundle\WebCareBundle\Controller\Studio\Site;

use CORS\Bundle\WebCareBundle\OpenApi\Config\Prefix;
use CORS\Bundle\WebCareBundle\OpenApi\Config\Tags;
use CORS\Bundle\WebCareBundle\Schema\WebCareSite;
use CORS\Bundle\WebCareBundle\Service\Studio\WebCareSiteServiceInterface;
use CORS\Bundle\WebCareBundle\Util\Constant\PermissionConstants;
use Exception;
use OpenApi\Attributes\Get;
use Pimcore\Bundle\StudioBackendBundle\Controller\AbstractApiController;
use Pimcore\Bundle\StudioBackendBundle\OpenApi\Attribute\Content\ItemsJson;
use Pimcore\Bundle\StudioBackendBundle\OpenApi\Attribute\Response\DefaultResponses;
use Pimcore\Bundle\StudioBackendBundle\OpenApi\Attribute\Response\SuccessResponse;
use Pimcore\Bundle\StudioBackendBundle\Util\Constant\HttpResponseCodes;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 */
final class CollectionController extends AbstractApiController
{
    private const string ROUTE = '/sites';

    public function __construct(
        SerializerInterface $serializer,
        private readonly WebCareSiteServiceInterface $webCareSiteService,
    ) {
        parent::__construct($serializer);
    }

    /**
     * @throws Exception
     */
    #[Route(path: self::ROUTE, name: 'pimcore_studio_api_bundle_web_care_sites', methods: ['GET'])]
    #[Get(
        path: Prefix::BUNDLE . self::ROUTE,
        operationId: 'bundle_web_care_site_get_collection',
        description: 'bundle_web_care_site_get_collection_description',
        summary: 'bundle_web_care_site_get_collection_summary',
        tags: [Tags::WebCare->value]
    )]
    #[IsGranted(PermissionConstants::WEB_CARE_SETTINGS)]
    #[SuccessResponse(
        description: 'bundle_web_care_site_get_collection_success_response',
        content: new ItemsJson(WebCareSite::class)
    )]
    #[DefaultResponses([
        HttpResponseCodes::UNAUTHORIZED,
    ])]
    public function getWebCareSites(): JsonResponse
    {
        return $this->jsonResponse(['items' => $this->webCareSiteService->listWebCareSites()]);
    }
}
