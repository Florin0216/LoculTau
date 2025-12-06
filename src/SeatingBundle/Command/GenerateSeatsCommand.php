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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S'
            ];

            foreach ($mainRows as $index => $rowLetter) {
                $rowNumber = $index + 1;
                $maxCols = $this->getSeatCountForRow($rowNumber);

                for ($c = 0; $c <= $maxCols; $c++) {
                    if (!$this->seatExists($room, $rowNumber, $c, 'Sala Principala')) {
                        $seat = new Seat();
                        $seat->setRoom($room);
                        $seat->setRowNo($rowNumber);
                        $seat->setNumber($c);
                        $seat->setSection('Sala Principala');
                        $this->entityManager->persist($seat);
                    }
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
            1 => 23, // A
            2 => 26, // B
            3 => 29, // C
            4 => 32, // D
            default => $this->calculateDynamicSeats($row),
        };
    }

    private function calculateDynamicSeats(int $row): int
    {
        $middleRow = 10; // J
        $seatsAtRow4 = 32;
        $seatsAtMiddle = $seatsAtRow4 + ($middleRow - 4);

        if ($row <= $middleRow) {
            return 32 + ($row - 4);
        }

        return $seatsAtMiddle - ($row - $middleRow);
    }

    private function generateLodgeSeats(Room $room, OutputInterface $output): void
    {
        // Lodge Left
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 8; $c++) {
                $seatNo = $c + ($r - 1) * 8; // row 1: 1-8, row 2: 9-16
                if (!$this->seatExists($room, $r, $seatNo, 'Loja Stanga')) {
                    $seat = new Seat();
                    $seat->setRoom($room);
                    $seat->setRowNo($r);
                    $seat->setNumber($seatNo);
                    $seat->setSection('Loja Stanga');
                    $this->entityManager->persist($seat);
                }
            }
        }

        // Lodge Right (mirrored)
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 8; $c++) {
                $seatNo = ($r === 1) ? (9 - $c) : (17 - $c);
                if(!$this->seatExists($room, $r, $seatNo, 'Loja Dreapta')) {
                    $seat = new Seat();
                    $seat->setRoom($room);
                    $seat->setRowNo($r);
                    $seat->setNumber($seatNo);
                    $seat->setSection('Loja Dreapta');
                    $this->entityManager->persist($seat);
                }
            }
        }


        // Lodge Middle (combined)
        for ($r = 1; $r <= 2; $r++) {
            for ($c = 1; $c <= 4; $c++) {
                $seat = new Seat();
                $seat->setRoom($room);
                $seat->setRowNo($r);
                $seat->setNumber($c + ($r - 1) * 4);
                $seat->setSection('Loja Oficiala Stanga');
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
                $seat->setSection('Loja Oficiala Dreapta');
                $this->entityManager->persist($seat);
            }
        }
    }

    private function seatExists(Room $room, int $row, int $number, string $section): bool
    {
        return (bool)$this->entityManager->getRepository(Seat::class)->findOneBy([
            'room' => $room,
            'rowNo' => $row,
            'number' => $number,
            'section' => $section,
        ]);
    }
}
