<?php

namespace SeatingBundle\Entity;

use AppBundle\Entity\Embeddable\FileEmbeddable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\SponsorRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
#[ORM\Table(name: 'seating__sponsor')]
#[Vich\Uploadable]
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

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    #[Groups('sponsor.details')]
    protected ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Embedded(class: FileEmbeddable::class, columnPrefix: 'image_')]
    protected ?FileEmbeddable $image = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'sponsors')]
    protected Collection $events;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): Sponsor
    {
        $this->color = $color;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Sponsor
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

    public function setImage(?FileEmbeddable $image): Sponsor
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): Sponsor
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

}
