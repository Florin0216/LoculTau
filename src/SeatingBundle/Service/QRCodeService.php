<?php

namespace SeatingBundle\Service;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Writer\PngWriter;
use SeatingBundle\Entity\Reservation;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class QRCodeService
{
    public function __construct(
        protected RouterInterface $router,
        protected UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function generate(Reservation $reservation): void
    {
        $claimUrl = $this->urlGenerator->generate('admin_seating_reservation_confirm', [
            'uuid' => $reservation->getUuid()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $builder = new Builder(
            writer: new PngWriter(),
            data: $claimUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 800,
            margin: 10,
        );

        $result = $builder->build();

        $tmpFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
        $result->saveToFile($tmpFile);

        $reservation->setQrCodeFile(new UploadedFile(
            $tmpFile,
            $reservation->getUuid() . '.png', // original name
            'image/png',
            null,
            true
        ));
    }
}
