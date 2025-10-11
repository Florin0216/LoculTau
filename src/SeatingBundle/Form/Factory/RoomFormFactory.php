<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Room;
use SeatingBundle\Form\Type\Room\RoomCreateType;
use SeatingBundle\Form\Type\Room\RoomEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RoomFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Room $room, array $options = []): FormInterface
    {
        return $this->formFactory->create(RoomCreateType::class, $room, $options);
    }

    public function getEditForm(Room $room, array $options = []): FormInterface
    {
        return $this->formFactory->create(RoomEditType::class, $room, $options);
    }
}
