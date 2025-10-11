<?php

namespace UserBundle\Event;

use AppBundle\DTO\RegistrationOrganizationDetailsDTO;
use Symfony\Contracts\EventDispatcher\Event;
use UserBundle\Entity\User;

class RegistrationCompletedEvent extends Event
{
    protected ?string $name = null;
    protected bool $isCompany = false;
    protected bool $isRestaurant = false;

    public function __construct(
        protected User $user,
        RegistrationOrganizationDetailsDTO $regDataDTO
    )
    {
        $this->name = $regDataDTO->name;
        $this->isCompany = $regDataDTO->isCompany;
        $this->isRestaurant = $regDataDTO->isRestaurant;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOrganizationName(): ?string
    {
        return $this->name;
    }

    public function isCompany(): bool
    {
        return $this->isCompany;
    }

    public function isRestaurant(): bool
    {
        return $this->isRestaurant;
    }
}
