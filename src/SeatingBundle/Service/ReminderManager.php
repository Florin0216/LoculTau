<?php

namespace SeatingBundle\Service;

use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Reminder;

class ReminderManager
{
    public function newInstance($scheduledAt,Event $event, $status, $email): Reminder
    {
        $reminder = new Reminder();

        $reminder
            ->setScheduledAt($scheduledAt)
            ->setStatus($status)
            ->setEvent($event)
            ->setEmail($email);

        return $reminder;
    }

}
