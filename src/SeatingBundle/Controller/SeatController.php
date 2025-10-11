<?php

namespace SeatingBundle\Controller;

use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Room;
use SeatingBundle\Entity\Seat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SeatController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected EntityService $es,
        protected SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function listAction($roomId, $eventId, Request $request): JsonResponse
    {
        $room = $this->es->findOrReject(Room::class, $roomId);

        $event = $this->es->findOrReject(Event::class, $eventId);

        $seats = $this->em->getRepository(Seat::class)->findBy(['room' => $room]);

        $seatGrid = [];
        foreach ($seats as $seat) {
            $seatGrid[$seat->getSection()][$seat->getRowNo()][$seat->getNumber()] = $seat;
        }

        $response = $this->serializer->normalize($seatGrid, null, [
            AbstractNormalizer::GROUPS => Seat::NORMALIZER_GROUPS,
            'event' => $event,
        ]);

        return new JsonResponse($response);
    }
}
