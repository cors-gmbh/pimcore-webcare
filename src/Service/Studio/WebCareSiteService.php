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

use CORS\Bundle\WebCareBundle\Entity\WebCareSite as WebCareSiteEntity;
use CORS\Bundle\WebCareBundle\Event\Studio\PreResponse\WebCareSiteEvent;
use CORS\Bundle\WebCareBundle\Hydrator\WebCareSiteHydratorInterface;
use CORS\Bundle\WebCareBundle\MappedParameter\CreateWebCareSiteParameters;
use CORS\Bundle\WebCareBundle\MappedParameter\UpdateWebCareSiteParameters;
use CORS\Bundle\WebCareBundle\Repository\WebCareSiteRepository;
use CORS\Bundle\WebCareBundle\Schema\WebCareSite;
use Doctrine\ORM\EntityManagerInterface;
use Pimcore\Bundle\StaticResolverBundle\Models\Site\SiteResolverInterface;
use Pimcore\Bundle\StudioBackendBundle\Exception\Api\ConflictException;
use Pimcore\Bundle\StudioBackendBundle\Exception\Api\NotFoundException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use function sprintf;

/**
 * @internal
 */
final readonly class WebCareSiteService implements WebCareSiteServiceInterface
{
    /**
     * Label used for the main site (siteId 0), matching the classic ExtJS grid.
     */
    private const string MAIN_SITE_DOMAIN = 'Home';

    public function __construct(
        private WebCareSiteRepository $webCareSiteRepository,
        private EntityManagerInterface $entityManager,
        private WebCareSiteHydratorInterface $webCareSiteHydrator,
        private SiteListingProviderInterface $siteListingProvider,
        private SiteResolverInterface $siteResolver,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function listWebCareSites(): array
    {
        $result = [];
        $processedSiteIds = [];

        foreach ($this->webCareSiteRepository->findAll() as $entity) {
            $siteId = $entity->getSiteId() ?? 0;

            if ($siteId === 0) {
                $result[] = $this->webCareSiteHydrator->hydrateWebCareSite($entity, self::MAIN_SITE_DOMAIN);
                $processedSiteIds[] = 0;

                continue;
            }

            $site = $this->siteResolver->getById($siteId);

            if ($site === null) {
                continue;
            }

            $result[] = $this->webCareSiteHydrator->hydrateWebCareSite($entity, $site->getMainDomain());
            $processedSiteIds[] = $site->getId();
        }

        foreach ($this->siteListingProvider->getSites() as $site) {
            if (in_array($site->getId(), $processedSiteIds, true)) {
                continue;
            }

            $result[] = $this->webCareSiteHydrator->hydrateUnconfiguredSite($site->getId(), $site->getMainDomain());
        }

        if (!in_array(0, $processedSiteIds, true)) {
            $result[] = $this->webCareSiteHydrator->hydrateUnconfiguredSite(0, self::MAIN_SITE_DOMAIN);
        }

        foreach ($result as $webCareSite) {
            $this->eventDispatcher->dispatch(new WebCareSiteEvent($webCareSite), WebCareSiteEvent::EVENT_NAME);
        }

        return $result;
    }

    public function createWebCareSite(CreateWebCareSiteParameters $parameters): WebCareSite
    {
        $existing = $this->webCareSiteRepository->findOneBy(['siteId' => $parameters->getSiteId()]);

        if ($existing !== null) {
            throw new ConflictException(
                sprintf('A WebCare configuration for site %d already exists', $parameters->getSiteId())
            );
        }

        $entity = new WebCareSiteEntity();
        $entity->setSiteId($parameters->getSiteId());
        $entity->setActive($parameters->isActive());
        $entity->setClientId($parameters->getClientId());
        $entity->setOrganizationId($parameters->getOrganizationId());
        $entity->setWebsiteId($parameters->getWebsiteId());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->dispatchAndReturn($entity);
    }

    public function updateWebCareSite(int $id, UpdateWebCareSiteParameters $parameters): WebCareSite
    {
        $entity = $this->loadEntity($id);

        $entity->setActive($parameters->isActive());
        $entity->setClientId($parameters->getClientId());
        $entity->setOrganizationId($parameters->getOrganizationId());
        $entity->setWebsiteId($parameters->getWebsiteId());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->dispatchAndReturn($entity);
    }

    public function deleteWebCareSite(int $id): void
    {
        $entity = $this->loadEntity($id);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @throws NotFoundException
     */
    private function loadEntity(int $id): WebCareSiteEntity
    {
        $entity = $this->webCareSiteRepository->find($id);

        if (!$entity instanceof WebCareSiteEntity) {
            throw new NotFoundException('web care site', $id, 'id');
        }

        return $entity;
    }

    private function dispatchAndReturn(WebCareSiteEntity $entity): WebCareSite
    {
        $siteId = $entity->getSiteId() ?? 0;
        $site = $siteId === 0 ? null : $this->siteResolver->getById($siteId);

        $response = $this->webCareSiteHydrator->hydrateWebCareSite(
            $entity,
            $site?->getMainDomain() ?? self::MAIN_SITE_DOMAIN
        );

        $this->eventDispatcher->dispatch(new WebCareSiteEvent($response), WebCareSiteEvent::EVENT_NAME);

        return $response;
    }
}
