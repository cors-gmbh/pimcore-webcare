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

namespace CORS\Bundle\WebCareBundle\Controller;

use CORS\Bundle\WebCareBundle\Entity\WebCareSite;
use CORS\Bundle\WebCareBundle\Repository\WebCareSiteRepository;
use Pimcore\Model\Site;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListController
{
    public function __invoke(WebCareSiteRepository $repository)
    {
        $sites = new Site\Listing();
        $all = $repository->findAll();
        $result = [];

        $sitesProcessed = [];

        foreach ($all as $config) {
            if ($config->getSiteId() === 0) {
                $result[] = [
                    'webCareId' => $config->getId(),
                    'siteId' => $config->getSiteId(),
                    'clientId' => $config->getClientId(),
                    'organizationId' => $config->getOrganizationId(),
                    'websiteId' => $config->getWebsiteId(),
                    'siteDomain' => 'Home',
                ];

                $sitesProcessed[] = 0;

                continue;
            }

            $site = Site::getById($config->getSiteId());

            if (!$site) {
                continue;
            }

            $result[] = [
                'webCareId' => $config->getId(),
                'siteId' => $config->getSiteId(),
                'clientId' => $config->getClientId(),
                'organizationId' => $config->getOrganizationId(),
                'websiteId' => $config->getWebsiteId(),
                'siteDomain' => $site->getMainDomain(),
            ];

            $sitesProcessed[] = $site->getId();
        }

        foreach ($sites as $site) {
            if (in_array($site->getId(), $sitesProcessed, true)) {
                continue;
            }

            $result[] = [
                'webCareId' => null,
                'siteId' => $site->getId(),
                'clientId' => null,
                'organizationId' => null,
                'websiteId' => null,
                'siteDomain' => $site->getMainDomain(),
            ];
        }

        if (!in_array(0, $sitesProcessed, true)) {
            $result[] = [
                'webCareId' => null,
                'siteId' => 0,
                'clientId' => null,
                'organizationId' => null,
                'websiteId' => null,
                'siteDomain' => 'Home',
            ];
        }

        return new JsonResponse($result);
    }
}
