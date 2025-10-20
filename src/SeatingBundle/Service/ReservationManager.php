<?php

namespace SeatingBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Exception\ValidationException;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;
use SeatingBundle\Entity\Sponsor;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ReservationManager
{
    public function __construct(
        protected QRCodeService          $QRCodeService,
        protected UploaderHelper         $uploaderHelper,
        protected EntityManagerInterface $em,
        protected ParameterBagInterface  $params,
    )
    {
    }

    public function newInstance(string $email, Seat $seat, Event $event, ?Sponsor $sponsor = null, string $name = null): Reservation
    {
        $reservation = new Reservation();

        $uuid = Uuid::v4()->toRfc4122();

        $reservation
            ->setSeat($seat)
            ->setEvent($event)
            ->setEmail($email)
            ->setName($name)
            ->setSponsor($sponsor)
            ->setUuid($uuid);

        return $reservation;
    }

    /**
     * @throws ValidationException
     */
    public function handleCreation(Reservation $reservation): array
    {
        $this->QRCodeService->generate($reservation);

        $this->em->persist($reservation);

        $qrCodePath = $this->uploaderHelper->asset($reservation, 'qrCodeFile');

        $privateDir = $this->params->get('private_files_dir');

        $fullPath = $privateDir . $qrCodePath;

        return [
            'seat' => $reservation->getSeat(),
            'qrCode' => $fullPath,
        ];
    }
}
