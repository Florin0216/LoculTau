<?php

namespace AppBundle\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait VichFileTrait
{
    #[ORM\Column(name: 'original_name', type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['file.details'])]
    protected ?string $originalName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    #[Groups(['file.details'])]
    protected ?string $mimeType = null;

    #[ORM\Column(type: Types::DECIMAL, nullable: true)]
    #[Groups(['file.details'])]
    protected ?float $size = null;

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(?float $size): static
    {
        $this->size = $size;

        return $this;
    }
}
