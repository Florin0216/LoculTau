<?php

namespace SeatingBundle\Form\Factory;

use SeatingBundle\Entity\Sponsor;
use SeatingBundle\Form\Type\Sponsor\SponsorCreateType;
use SeatingBundle\Form\Type\Sponsor\SponsorEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class SponsorFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Sponsor $sponsor, array $options = []): FormInterface
    {
        return $this->formFactory->create(SponsorCreateType::class, $sponsor, $options);
    }

    public function getEditForm(Sponsor $sponsor, array $options = []): FormInterface
    {
        return $this->formFactory->create(SponsorEditType::class, $sponsor, $options);
    }

}
