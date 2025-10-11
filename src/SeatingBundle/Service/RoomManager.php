<?php

namespace SeatingBundle\Service;

use SeatingBundle\Entity\Room;

class RoomManager
{
    public function newInstance(string $name = null): Room
    {
        $room = new Room();

        $room->setName($name);

        return $room;
    }
}
