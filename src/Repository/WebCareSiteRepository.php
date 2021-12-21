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

namespace CORS\Bundle\WebCareBundle\Repository;

use CORS\Bundle\WebCareBundle\Entity\WebCareSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pimcore\Model\Site;

class WebCareSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebCareSite::class);
    }

    public function findDefault(bool $active = false): ?WebCareSite
    {
        return $this->findOneBy(['siteId' => 0, 'active' => $active]);
    }

    public function findForSite(Site $site, bool $active = false): ?WebCareSite
    {
        return $this->findOneBy(['siteId' => $site->getId(), 'active' => $active]);
    }
}
