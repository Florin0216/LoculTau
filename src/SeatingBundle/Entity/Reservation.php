<?php

namespace SeatingBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Seat::class)]
    private ?Seat $seat = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    private ?Event $event = null;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $status = 'pending';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): void
    {
        $this->seat = $seat;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


}
