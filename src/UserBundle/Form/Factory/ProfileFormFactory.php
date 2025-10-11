<?php

namespace UserBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\User;
use UserBundle\Form\Type\ProfileEditType;

class ProfileFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getEditForm(User|UserInterface $user, array $options = []): FormInterface
    {
        return $this->formFactory->create(ProfileEditType::class, $user, $options);
    }
}
