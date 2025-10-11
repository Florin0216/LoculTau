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

            $mainRows = [
                'A','B','C','D','E','F','G','H','I','J',
                'K','L','M','N','O','P','R','S'
            ];

            foreach ($mainRows as $index => $rowLetter) {
                $rowNumber = $index + 1;
                $maxCols = $this->getSeatCountForRow($rowNumber);

                for ($c = 1; $c <= $maxCols; $c++) {
                    $seat = new Seat();
                    $seat->setRoom($room);
                    $seat->setRowNo($rowNumber);
                    $seat->setNumber($c);
                    $seat->setSection('main');
                    $this->entityManager->persist($seat);
                }
            }

            $this->generateLodgeSeats($room, $output);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function getSeatCountForRow(int $row): int
    {
        return match ($row) {
            1 => 22, // A
            2 => 25, // B
            3 => 28, // C
            4 => 31, // D
            default => $this->calculateDynamicSeats($row),
        };
    }

    private function calculateDynamicSeats(int $row): int
    {
        $middleRow = 10; // J
        $seatsAtRow4 = 31;
        $seatsAtMiddle = $seatsAtRow4 + ($middleRow - 4);

        if ($row <= $middleRow) {
            return 31 + ($row - 4);
        }

        return $seatsAtMiddle - ($row - $middleRow);
    }

    private function generateLodgeSeats(Room $room, OutputInterface $output): void
    {
        // Lodge Left
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 4; $c++) {
                $seat = new Seat();
                $seat->setRoom($room);
                $seat->setRowNo($r);
                $seatNo = $c + ($r - 1) * 4; // row 1: 1-4, row 2: 5-8
                $seat->setNumber($seatNo);
                $seat->setSection('lodge_left');
                $this->entityManager->persist($seat);
            }
        }

        // Lodge Right (mirrored)
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 4; $c++) {
                $seat = new Seat();
                $seat->setRoom($room);
                $seat->setRowNo($r);

                if ($r === 1) {
                    $seatNo = 5 - $c;
                } else {
                    $seatNo = 9 - $c;
                }

                $seat->setNumber($seatNo);
                $seat->setSection('lodge_right');
                $this->entityManager->persist($seat);
            }
        }


        // Lodge Middle (combined)
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 4; $c++) {
                $seat = new Seat();
                $seat->setRoom($room);
                $seat->setRowNo($r);
                $seat->setNumber($c + ($r - 1) * 4);
                $seat->setSection('lodge_middle_left');
                $this->entityManager->persist($seat);
            }

            for ($c = 1; $c <= 4; $c++) {
                $seat = new Seat();
                $seat->setRoom($room);
                $seat->setRowNo($r);
                if ($r === 1) {
                    $seatNo = 5 - $c;
                } else {
                    $seatNo = 9 - $c;
                }
                $seat->setNumber($seatNo);
                $seat->setSection('lodge_middle_right');
                $this->entityManager->persist($seat);
            }
        }

    }
}
