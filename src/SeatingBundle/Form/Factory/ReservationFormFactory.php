<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Reservation;
use SeatingBundle\Form\Type\Reservation\ReservationCreateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ReservationFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Reservation $reservation, array $options = []): FormInterface
    {
        return $this->formFactory->create(ReservationCreateType::class, $reservation, $options);
    }
}
