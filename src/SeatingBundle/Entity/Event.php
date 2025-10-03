<?php

namespace SeatingBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\EventRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('event')]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups('event')]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[Groups('event')]
    private ?Room $room = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): void
    {
        $this->room = $room;
    }



}
