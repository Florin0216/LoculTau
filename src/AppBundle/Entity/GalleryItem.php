<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Embeddable\FileEmbeddable;
use AppBundle\Repository\GalleryItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: GalleryItemRepository::class)]
#[ORM\Table(name: 'app__gallery_item')]
#[Vich\Uploadable]
class GalleryItem
{
    const ENTITY_ALIAS = 'glt';

    const NORMALIZER_GROUPS = ['gallery.item.details','gallery.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('gallery.item.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('gallery.item.details')]
    protected string $title;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Embedded(class: FileEmbeddable::class, columnPrefix: 'image_')]
    protected ?FileEmbeddable $image = null;

    #[ORM\Embedded(class: FileEmbeddable::class, columnPrefix: 'thumbnail_')]
    protected ?FileEmbeddable $thumbnail = null;

    #[ORM\ManyToOne(targetEntity: Gallery::class)]
    #[Groups('gallery.details')]
    protected ?Gallery $gallery = null;

    #[Vich\UploadableField(
        mapping: 'app_images_public_storage',
        fileNameProperty: 'image.name',
        size: 'image.size',
        mimeType: 'image.mimeType',
        originalName: 'image.originalName'
    )]
    protected ?File $imageFile = null;

    #[Vich\UploadableField(
        mapping: 'app_images_public_storage',
        fileNameProperty: 'thumbnail.name',
        size: 'thumbnail.size',
        mimeType: 'thumbnail.mimeType',
        originalName: 'thumbnail.originalName'
    )]
    protected ?File $thumbnailFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): GalleryItem
    {
        $this->title = $title;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): GalleryItem
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

    public function setImage(?FileEmbeddable $image): GalleryItem
    {
        $this->image = $image;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): GalleryItem
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getThumbnail(): ?FileEmbeddable
    {
        if (!$this->thumbnail) {
            $this->thumbnail = new FileEmbeddable();
        }
        return $this->thumbnail;
    }

    public function setThumbnail(?FileEmbeddable $thumbnail): GalleryItem
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): GalleryItem
    {
        $this->thumbnailFile = $thumbnailFile;

        if ($thumbnailFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): GalleryItem
    {
        $this->gallery = $gallery;
        return $this;
    }

}
