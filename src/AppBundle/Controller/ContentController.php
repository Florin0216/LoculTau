<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends AbstractController
{
    public function __construct(){

    }

    public function showAboutAction(): Response
    {
        return $this->render('@App/Content/public/about.html.twig');
    }

    public function showContactAction(): Response
    {
        return $this->render('@App/Content/public/contact.html.twig');
    }

}
