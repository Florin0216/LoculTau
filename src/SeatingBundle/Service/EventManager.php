<?php

namespace SeatingBundle\Service;

use SeatingBundle\Entity\Event;

class EventManager
{
    public function newInstance($date, ?string $title = null): Event
    {
        $event = new Event();

        $event
            ->setDate($date)
            ->setTitle($title);

        return $event;
    }
}
