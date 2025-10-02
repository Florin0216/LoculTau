<?php

namespace SeatingBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Room;
use SeatingBundle\Entity\Seat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function display(int $id): Response
    {
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        return $this->render('@Seating/Room/public/display.html.twig',[
            'room' => $room,
        ]);
    }

}
