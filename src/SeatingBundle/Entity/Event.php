<?php

namespace SeatingBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\EventRepository;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'seating__event')]
class Event
{
    const ENTITY_ALIAS = 'evt';

    const NORMALIZER_GROUPS = ['event.details', 'room.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('event.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('event.details')]
    protected string $title;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i'])]
    #[Groups(['event.details'])]
    protected ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[Groups(['room.details'])]
    protected ?Room $room = null;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): Event
    {
        $this->date = $date;

        return $this;
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
