<?php

namespace UserBundle\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use UserBundle\Entity\User;

class UserManager
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function newInstance(string $email, string $password, string $username = null, string $firstName = null, string $lastName = null): User
    {
        $user = new User();

        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($hashedPassword);

        return $user;
    }
}
