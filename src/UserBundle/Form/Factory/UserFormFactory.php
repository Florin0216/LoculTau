<?php

namespace UserBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use UserBundle\Entity\User;
use UserBundle\Form\Type\User\UserCreateType;
use UserBundle\Form\Type\User\UserEditType;

class UserFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(User $user, array $options = []): FormInterface
    {
        return $this->formFactory->create(UserCreateType::class, $user, $options);
    }

    public function getEditForm(User $user, array $options = []): FormInterface
    {
        return $this->formFactory->create(UserEditType::class, $user, $options);
    }
}
