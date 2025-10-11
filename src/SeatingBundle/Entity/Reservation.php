<?php

namespace SeatingBundle\Entity;

use AppBundle\Entity\Embeddable\FileEmbeddable;
use AppBundle\Entity\Trait\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SeatingBundle\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'seating__reservation')]
#[Vich\Uploadable]
class Reservation
{
    const ENTITY_ALIAS = 'rsv';

    const NORMALIZER_GROUPS = ['reservation.details', 'seat.details', 'event.details'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['reservation.details'])]
    protected ?int $id;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: false)]
    #[Groups(['reservation.details'])]
    protected ?string $email;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    #[Groups(['reservation.details'])]
    protected ?string $name;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: false)]
    #[Groups(['reservation.details'])]
    protected ?string $uuid = null;

    #[ORM\Column(type:  Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['reservation.details'])]
    protected ?\DateTimeImmutable $claimedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Embedded(class: FileEmbeddable::class, columnPrefix: 'qr_code_')]
    protected ?FileEmbeddable $qrCode = null;

    #[ORM\ManyToOne(targetEntity: Seat::class)]
    #[ORM\JoinColumn(name: 'seat_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['seat.details'])]
    protected ?Seat $seat = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['event.details'])]
    protected ?Event $event = null;

    #[Vich\UploadableField(
        mapping: 'app_images_private_storage',
        fileNameProperty: 'qrCode.name',
        size: 'qrCode.size',
        mimeType: 'qrCode.mimeType',
        originalName: 'qrCode.originalName'
    )]
    protected ?File $qrCodeFile = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Reservation
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Reservation
    {
        $this->name = $name;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): Reservation
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getClaimedAt(): ?\DateTimeImmutable
    {
        return $this->claimedAt;
    }

    public function setClaimedAt(?\DateTimeImmutable $claimedAt): Reservation
    {
        $this->claimedAt = $claimedAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Reservation
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): Reservation
    {
        $this->seat = $seat;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): Reservation
    {
        $this->event = $event;

        return $this;
    }

    public function getQrCode(): ?FileEmbeddable
    {
        if (!$this->qrCode) {
            $this->qrCode = new FileEmbeddable();
        }

        return $this->qrCode;
    }

    public function setQrCode(?FileEmbeddable $qrCode): Reservation
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getQrCodeFile(): ?File
    {
        return $this->qrCodeFile;
    }

    public function setQrCodeFile(?File $qrCodeFile): Reservation
    {
        $this->qrCodeFile = $qrCodeFile;

        if (!$qrCodeFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }
}
