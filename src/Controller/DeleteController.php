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

use CORS\Bundle\WebCareBundle\Repository\WebCareSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteController
{
    public function __invoke(
        Request $request,
        WebCareSiteRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $config = $repository->find($request->get('id'));

        if (!$config) {
            return new JsonResponse(['success' => false]);
        }

        $entityManager->remove($config);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
