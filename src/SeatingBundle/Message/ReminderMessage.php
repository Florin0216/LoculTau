<?php

namespace SeatingBundle\Message;

class ReminderMessage
{
    public function __construct(
        protected int $reminderId,
    ) {
    }

    public function getReminderId(): int
    {
        return $this->reminderId;
    }

}
