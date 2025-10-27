<?php

namespace SeatingBundle\Command;

use SeatingBundle\Message\ReminderMessage;
use SeatingBundle\Repository\ReminderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

#[AsCommand(name: 'seating:queue-reminders')]
class QueueRemindersCommand extends Command
{
    public function __construct(
        protected ReminderRepository  $reminderRepository,
        protected MessageBusInterface $bus
    )
    {
        parent::__construct();
    }

    /**
     * @throws ExceptionInterface
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable('now');
        $nextMinute = $now->modify('+1 minute');

        $reminders = $this->reminderRepository->findPendingBetween($now, $nextMinute);

        foreach ($reminders as $reminder) {
            $scheduledAt = $reminder->getScheduledAt();
            $delayInSeconds = $scheduledAt->getTimestamp() - $now->getTimestamp();
            $delayMs = max(0, $delayInSeconds * 1000);

            $this->bus->dispatch(
                new ReminderMessage($reminder->getId()),
                [new DelayStamp($delayMs)]
            );

            $reminder->setStatus('queued');
        }

        $this->reminderRepository->saveAll($reminders);

        return Command::SUCCESS;
    }
}
