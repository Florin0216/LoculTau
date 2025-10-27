<?php

namespace AppBundle\Entity;

use AppBundle\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'app__feedback')]
class Feedback
{
    const ENTITY_ALIAS = 'fb';

    const NORMALIZER_GROUPS = ['feedback.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups('feedback.details')]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Groups('feedback.details')]
    protected string $name;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: false)]
    #[Groups(['feedback.details'])]
    protected ?string $email;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['feedback.details'])]
    protected ?string $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Feedback
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Feedback
    {
        $this->email = $email;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Feedback
    {
        $this->message = $message;
        return $this;
    }

}
