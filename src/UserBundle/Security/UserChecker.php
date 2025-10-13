<?php

namespace UserBundle\Security;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\User;

class UserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isDisabled()) {
            throw new AccessDeniedException('This account has been disabled');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user): void
    {

    }
}
