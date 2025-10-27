<?php

namespace SeatingBundle\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Reminder;
use SeatingBundle\Message\ReminderMessage;
use SeatingBundle\Repository\ReminderRepository;
use SeatingBundle\Service\MailerService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReminderMessageHandler
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ReminderRepository $reminderRepository,
        protected MailerService $mailerService,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(ReminderMessage $message): void
    {
        $reminder = $this->em->getRepository(Reminder::class)->find($message->getReminderId());

        $this->mailerService->sendReminderEmail($reminder->getEmail(), $reminder->getEvent());

        $reminder->setStatus('sent');
        $this->em->flush();
    }

}
