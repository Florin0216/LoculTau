<?php

namespace SeatingBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Twig\Environment;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MailerService
{
    public function __construct(
        protected MailerInterface       $mailer,
        protected Environment           $twig,
        protected UploaderHelper        $uploaderHelper,
        protected ParameterBagInterface $params,
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
                $cid = 'qr_code_' . $index . '.png';

                $email->embedFromPath($ticket['qrCode'], $cid);

                $reservationData[$index]['qrCodeCid'] = 'cid:' . $cid;
            }
        }

        $email
            ->htmlTemplate('@Seating/Email/public/reservation.html.twig')
            ->context([
                'ticketData' => $reservationData,
            ]);

        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendReminderEmail(string $to, $event): void
    {
        $email = (new TemplatedEmail())
            ->to($to)
            ->subject('Reminder');

        $relativePath = $this->params->get('public_files_dir') . $this->uploaderHelper->asset($event, 'imageFile');
        $fullPath = $this->params->get('kernel.project_dir') . '/public' . $relativePath;

        if (file_exists($fullPath)) {
            $cid = 'event_image.' . pathinfo($fullPath, PATHINFO_EXTENSION);;
            $email->embedFromPath($fullPath, $cid);
            $imageCid = 'cid:' . $cid;
        }

        $email
            ->htmlTemplate('@Seating/Email/public/reminder.html.twig')
            ->context([
                'event' => $event,
                'imageCid' => $imageCid,
            ]);

        $this->mailer->send($email);
    }
}
