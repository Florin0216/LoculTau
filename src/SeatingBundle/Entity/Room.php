<?php

namespace SeatingBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\RoomRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ORM\Table(name: 'seating__room')]
class Room
{
    const ENTITY_ALIAS = 'rm';

    const NORMALIZER_GROUPS = ['room.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('room.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('room.details')]
    protected ?string $name;

    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'room')]
    protected Collection $seats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function setSeats(Collection $seats): void
    {
        $this->seats = $seats;
    }



}
