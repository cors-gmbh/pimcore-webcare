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
use CORS\Bundle\WebCareBundle\Form\Type\WebCareSiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateController
{
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager
    ) {
        $body = json_decode($request->getContent(), true);

        $config = new WebCareSite();
        $form = $formFactory->createNamed('', WebCareSiteType::class, $config, []);
        $form = $form->submit($body);

        if ($form->isSubmitted() && $form->isValid()) {
            $config = $form->getData();

            $entityManager->persist($config);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $config->getId()]);
        }

        return new JsonResponse(['success' => false]);
    }
}
