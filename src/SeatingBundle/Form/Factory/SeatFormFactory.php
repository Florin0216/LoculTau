<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Seat;
use SeatingBundle\Form\Type\Seat\SeatCreateType;
use SeatingBundle\Form\Type\Seat\SeatEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class SeatFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Seat $seat, array $options = []): FormInterface
    {
        return $this->formFactory->create(SeatCreateType::class, $seat, $options);
    }

    public function getEditForm(Seat $seat, array $options = []): FormInterface
    {
        return $this->formFactory->create(SeatEditType::class, $seat, $options);
    }

}
