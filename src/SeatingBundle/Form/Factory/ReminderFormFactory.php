<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Reminder;
use SeatingBundle\Form\Type\Reminder\ReminderCreateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ReminderFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Reminder $reminder, array $options = []): FormInterface
    {
        return $this->formFactory->create(ReminderCreateType::class, $reminder, $options);
    }

}
