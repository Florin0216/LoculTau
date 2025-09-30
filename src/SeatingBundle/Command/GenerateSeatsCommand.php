<?php

namespace SeatingBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Room;
use SeatingBundle\Entity\Seat;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'seating:generate-seats')]
class GenerateSeatsCommand extends Command
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rooms = $this->entityManager->getRepository(Room::class)->findAll();

        foreach ($rooms as $room) {
            $rows = $room->getRows();
            $cols = $room->getCols();

            for ($r = 1; $r <= $rows; $r++) {

                $maxCols = ($r == 1 || $r == $rows) ? 20 : $cols;

                for ($c = 1; $c <= $maxCols; $c++) {
                    $seat = new Seat();
                    $seat->setRoom($room);
                    $seat->setRowNo($r);
                    $seat->setSeatNo($c);
                    $seat->setStatus('available');

                    $this->entityManager->persist($seat);
                }
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

}
