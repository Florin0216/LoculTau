<?php

namespace SeatingBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\RoomRepository;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $rows;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $cols;

    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'room')]
    private Collection $seats;

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

    public function getRows(): ?int
    {
        return $this->rows;
    }

    public function setRows(?int $rows): void
    {
        $this->rows = $rows;
    }

    public function getCols(): ?int
    {
        return $this->cols;
    }

    public function setCols(?int $cols): void
    {
        $this->cols = $cols;
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
