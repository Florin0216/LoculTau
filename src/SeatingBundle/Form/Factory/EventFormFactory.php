<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Event;
use SeatingBundle\Form\Type\Event\EventCreateType;
use SeatingBundle\Form\Type\Event\EventEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class EventFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Event $event, array $options = []): FormInterface
    {
        return $this->formFactory->create(EventCreateType::class, $event, $options);
    }

    public function getEditForm(Event $event, array $options = []): FormInterface
    {
        return $this->formFactory->create(EventEditType::class, $event, $options);
    }
}
