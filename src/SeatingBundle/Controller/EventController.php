<?php

namespace SeatingBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class EventController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function fetchEvents(SerializerInterface $serializer): JsonResponse
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();

        $response = $serializer->serialize($events,'json',['groups' => 'event']);

        return new JsonResponse($response);
    }

    public function displayEvents(): Response
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();

        return $this->render('@Seating/Event/public/displayEvents.html.twig',[
        ]);
    }

}
