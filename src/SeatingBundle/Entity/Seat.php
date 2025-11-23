<?php

namespace SeatingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\SeatRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
#[ORM\Table(name: 'seating__seat')]
class Seat
{
    const ENTITY_ALIAS = 'st';

    const NORMALIZER_GROUPS = ['seat.details', 'room.details', 'sponsor.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('seat.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('seat.details')]
    protected ?int $rowNo;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('seat.details')]
    protected ?int $number;

    #[ORM\Column(type: Types::STRING, length: 32)]
    #[Groups('seat.details')]
    protected ?string $section;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'seats')]
    #[Groups('room.details')]
    protected ?Room $room;

    #[ORM\ManyToOne(targetEntity: Sponsor::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('sponsor.details')]
    protected ?Sponsor $sponsor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRowNo(): ?int
    {
        return $this->rowNo;
    }

    public function setRowNo(?int $rowNo): void
    {
        $this->rowNo = $rowNo;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): void
    {
        $this->section = $section;
    }

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): Seat
    {
        $this->sponsor = $sponsor;
        return $this;
    }

}
