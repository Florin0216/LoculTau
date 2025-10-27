<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    )
    {
    }

    public function showAboutAction(): Response
    {
        $pages = $this->entityManager->getRepository(Page::class)->findBy(['slug' => 'about']);

        $pageSections = [];
        foreach ($pages as $page) {
            $pageSections[$page->getSection()] = $page->getContent();
        }

        return $this->render('@App/Content/public/about.html.twig', [
            'pageSections' => $pageSections,
        ]);
    }

}
