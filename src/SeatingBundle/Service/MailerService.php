<?php

namespace SeatingBundle\Service;

use SeatingBundle\Entity\Reservation;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Twig\Environment;

class MailerService
{
    public function __construct(
        protected MailerInterface $mailer,
        protected Environment $twig,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $to, array $reservationData): void
    {
        $email = (new TemplatedEmail())
            ->to($to)
            ->subject('Confirmare rezervare');

        foreach ($reservationData as $index => $ticket) {
            if (isset($ticket['qrCode']) && file_exists($ticket['qrCode'])) {
                $cid = 'qr_code_'.$index.'.png';

                $email->embedFromPath($ticket['qrCode'], $cid);

                $reservationData[$index]['qrCodeCid'] = 'cid:'.$cid;
            }
        }

        $email
            ->htmlTemplate('@Seating/Email/public/reservation.html.twig')
            ->context([
                'ticketData' => $reservationData,
            ]);

        $this->mailer->send($email);
    }
}
