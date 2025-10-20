<?php

namespace SeatingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\SponsorRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
#[ORM\Table(name: 'seating__sponsor')]
class Sponsor
{

    const ENTITY_ALIAS = 'sps';

    const NORMALIZER_GROUPS = ['sponsor.details', 'event.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('sponsor.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('sponsor.details')]
    protected string $name;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: false)]
    #[Groups(['sponsor.details'])]
    protected ?string $uuid = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'sponsors')]
    protected Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Sponsor
    {
        $this->name = $name;
        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): Sponsor
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function setEvents(Collection $events): Sponsor
    {
        $this->events = $events;
        return $this;
    }

}
