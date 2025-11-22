<?php

namespace AppBundle\Entity;

use AppBundle\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Entity\Event;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
#[ORM\Table(name: 'app__gallery')]
class Gallery
{
    const ENTITY_ALIAS = 'glr';

    const NORMALIZER_GROUPS = ['gallery.details','event.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('gallery.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('gallery.details')]
    protected ?string $title = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups('gallery.details')]
    protected ?bool $isGeneral = false;

    #[ORM\OneToMany(targetEntity: GalleryItem::class, mappedBy: 'gallery', cascade: ['remove'], orphanRemoval: true)]
    protected Collection $galleryItems;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    #[Groups(['event.details'])]
    protected ?Event $event = null;

    public function __construct()
    {
        $this->galleryItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Gallery
    {
        $this->title = $title;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): Gallery
    {
        $this->event = $event;
        return $this;
    }

    public function isGeneral(): bool
    {
        return $this->isGeneral;
    }

    public function setIsGeneral(bool $isGeneral): Gallery
    {
        $this->isGeneral = $isGeneral;
        return $this;
    }


}
