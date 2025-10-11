<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomepageController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $em,)
    {

    }

    public function homepageAction(Request $request): Response
    {
        return $this->render('@App/Homepage/homepage.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    public function homepageAdminAction(Request $request): Response
    {
        return $this->render('@App/Homepage/admin/homepage.html.twig');
    }
}
