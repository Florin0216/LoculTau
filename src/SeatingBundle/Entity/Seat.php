<?php

namespace SeatingBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\SeatRepository;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $rowNo;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $seatNo;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private ?string $status;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'seats')]
    private Room $room;

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

    public function getSeatNo(): ?int
    {
        return $this->seatNo;
    }

    public function setSeatNo(?int $seatNo): void
    {
        $this->seatNo = $seatNo;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }


}
