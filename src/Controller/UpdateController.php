<?php

declare(strict_types=1);

/*
 * Austrian Standards Operations GmbH
 *
 * This software is available under the GNU General Public License version 3 (GPLv3).
 *
 * @copyright  Copyright (c) Austrian Standards Operations GmbH (https://www.austrian-standards.at)
 * @license    GPLv3
 */

namespace CORS\Bundle\WebCareBundle\Controller;

use CORS\Bundle\WebCareBundle\Form\Type\WebCareSiteType;
use CORS\Bundle\WebCareBundle\Repository\WebCareSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateController
{
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        WebCareSiteRepository $repository,
        EntityManagerInterface $entityManager,
    ) {
        $body = json_decode($request->getContent(), true);
        /** @psalm-suppress InternalMethod **/
        $config = $repository->find($request->get('id'));

        if (!$config) {
            return new JsonResponse(['success' => false]);
        }

        $form = $formFactory->create(WebCareSiteType::class, $config, []);
        $form->submit($body);

        if ($form->isSubmitted() && $form->isValid()) {
            $config = $form->getData();

            $entityManager->persist($config);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'id' => $config->getId()]);
        }

        return new JsonResponse(['success' => false]);
    }
}
