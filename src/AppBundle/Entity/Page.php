<?php

namespace AppBundle\Entity;

use AppBundle\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\Table(name: 'app__page')]
#[ORM\UniqueConstraint(name: 'app__unique_section_slug', columns: ['section', 'slug'])]
class Page
{

    const ENTITY_ALIAS = 'page';

    const NORMALIZER_GROUPS = ['page.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('page.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('page.details')]
    protected string $title;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('page.details')]
    protected string $slug;

    #[ORM\Column(type: Types::STRING, length: 64)]
    #[Groups('page.details')]
    protected string $section;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('page.details')]
    protected ?string $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Page
    {
        $this->title = $title;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Page
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function setSection(string $section): Page
    {
        $this->section = $section;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Page
    {
        $this->content = $content;
        return $this;
    }

}
