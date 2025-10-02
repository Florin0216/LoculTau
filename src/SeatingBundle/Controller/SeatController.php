<?php

namespace SeatingBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Seat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SeatController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function fetchSeats(SerializerInterface $serializer): JsonResponse
    {
        $seats = $this->entityManager->getRepository(Seat::class)->findAll();

        $seatGrid = [];
        foreach ($seats as $seat) {
            $seatGrid[$seat->getRowNo()][$seat->getSeatNo()] = $seat;
        }

        $response = $serializer->serialize($seatGrid,'json',['groups' => 'seat']);


        return new JsonResponse($response);
    }

}
