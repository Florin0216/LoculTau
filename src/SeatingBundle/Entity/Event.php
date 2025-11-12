<?php

namespace SeatingBundle\Entity;

use AppBundle\Entity\Embeddable\FileEmbeddable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\EventRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'seating__event')]
#[Vich\Uploadable]
class Event
{
    const ENTITY_ALIAS = 'evt';

    const NORMALIZER_GROUPS = ['event.details', 'room.details', 'sponsor.details'];

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

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Embedded(class: FileEmbeddable::class, columnPrefix: 'image_')]
    protected ?FileEmbeddable $image = null;

    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[Groups(['room.details'])]
    protected ?Room $room = null;

    #[ORM\ManyToMany(targetEntity: Sponsor::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'seating__event_sponsor')]
    #[Groups(['sponsor.details'])]
    protected ?Collection $sponsors = null;

    #[Vich\UploadableField(
        mapping: 'app_images_public_storage',
        fileNameProperty: 'image.name',
        size: 'image.size',
        mimeType: 'image.mimeType',
        originalName: 'image.originalName'
    )]
    protected ?File $imageFile = null;

    public function __construct()
    {
        $this->sponsors = new ArrayCollection();
    }

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Event
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImage(): ?FileEmbeddable
    {
        if (!$this->image) {
            $this->image = new FileEmbeddable();
        }

        return $this->image;
    }

    public function setImage(?FileEmbeddable $image): Event
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): Event
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

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

    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function setSponsors(Collection $sponsors): Event
    {
        $this->sponsors = $sponsors;
        return $this;
    }

}
