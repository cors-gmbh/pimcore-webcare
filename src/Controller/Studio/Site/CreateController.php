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

use CORS\Bundle\WebCareBundle\Attribute\Request\CreateWebCareSiteRequestBody;
use CORS\Bundle\WebCareBundle\MappedParameter\CreateWebCareSiteParameters;
use CORS\Bundle\WebCareBundle\OpenApi\Config\Prefix;
use CORS\Bundle\WebCareBundle\OpenApi\Config\Tags;
use CORS\Bundle\WebCareBundle\Schema\WebCareSite;
use CORS\Bundle\WebCareBundle\Service\Studio\WebCareSiteServiceInterface;
use CORS\Bundle\WebCareBundle\Util\Constant\PermissionConstants;
use Exception;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use Pimcore\Bundle\StudioBackendBundle\Controller\AbstractApiController;
use Pimcore\Bundle\StudioBackendBundle\Exception\Api\ConflictException;
use Pimcore\Bundle\StudioBackendBundle\OpenApi\Attribute\Response\DefaultResponses;
use Pimcore\Bundle\StudioBackendBundle\OpenApi\Attribute\Response\SuccessResponse;
use Pimcore\Bundle\StudioBackendBundle\Util\Constant\HttpResponseCodes;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 */
final class CreateController extends AbstractApiController
{
    private const string ROUTE = '/sites';

    public function __construct(
        SerializerInterface $serializer,
        private readonly WebCareSiteServiceInterface $webCareSiteService,
    ) {
        parent::__construct($serializer);
    }

    /**
     * @throws ConflictException
     * @throws Exception
     */
    #[Route(path: self::ROUTE, name: 'pimcore_studio_api_bundle_web_care_create_site', methods: ['POST'])]
    #[Post(
        path: Prefix::BUNDLE . self::ROUTE,
        operationId: 'bundle_web_care_site_create',
        description: 'bundle_web_care_site_create_description',
        summary: 'bundle_web_care_site_create_summary',
        tags: [Tags::WebCare->value]
    )]
    #[IsGranted(PermissionConstants::WEB_CARE_SETTINGS)]
    #[CreateWebCareSiteRequestBody]
    #[SuccessResponse(
        description: 'bundle_web_care_site_create_success_response',
        content: new JsonContent(ref: WebCareSite::class, type: 'object')
    )]
    #[DefaultResponses([
        HttpResponseCodes::UNAUTHORIZED,
    ])]
    public function createWebCareSite(
        #[MapRequestPayload] CreateWebCareSiteParameters $parameters,
    ): JsonResponse {
        return $this->jsonResponse($this->webCareSiteService->createWebCareSite($parameters));
    }
}
