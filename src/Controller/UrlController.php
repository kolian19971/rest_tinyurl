<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Url;

class UrlController extends AbstractController
{
    /**
     * @Route("/{slug}", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, string $slug): Response
    {
        $nowDate = new \DateTime();
        $entityManager = $doctrine->getManager();
        $url = $doctrine->getRepository(Url::class)->findOneBy([
            'slug' => $slug
        ]);

        if(!$url instanceof Url)
            return new Response('Not found', 404);

        if($url->getFinishedAt() instanceof \DateTime && $nowDate > $url->getFinishedAt())
            return new Response('Url lifetime expired', 423);

        // update visit counter
        $url->setVisitCount($url->getVisitCount() + 1);
        $entityManager->persist($url);
        $entityManager->flush();

        return $this->redirect($url->getOriginUrl());
    }
}