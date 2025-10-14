<?php

namespace SeatingBundle\Serializer\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SeatNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        protected NormalizerInterface $normalizer,
        protected EntityManagerInterface $em,
        protected Security $security,
    )
    {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $normalizedData = $this->normalizer->normalize($data, $format, $context);

        if (isset($context['event'])) {
            $event = $context['event'];

            $reservationRepo = $this->em->getRepository(Reservation::class);
            $reservation = $reservationRepo->findByEventAndSeat($event, $data);

            $normalizedData['isAvailable'] = !$reservation;

            $normalizedData['isSelected'] = false;

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $normalizedData['claimedAt'] = $reservation?->getClaimedAt();
            }
        }

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Seat;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Seat::class => true,
        ];
    }
}
