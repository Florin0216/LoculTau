<?php

namespace SeatingBundle\Controller;

use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Exception\ValidationException;
use Knp\Component\Pager\PaginatorInterface;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;
use SeatingBundle\Form\Factory\ReservationFormFactory;
use SeatingBundle\Service\MailerService;
use SeatingBundle\Service\QRCodeService;
use SeatingBundle\Service\ReservationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ReservationController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ReservationFormFactory $formFactory,
        protected MailerService          $mailer,
        protected ReservationManager     $reservationManager,
        protected QRCodeService          $QRCodeService,
        protected SerializerInterface    $serializer,
        protected ParameterBagInterface  $params,
        protected EntityService          $es,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws TransportExceptionInterface
     */
    public function newAction(Request $request, UploaderHelper $uploaderHelper): Response
    {
        $reservation = new Reservation();

        $form = $this->formFactory->getCreateForm($reservation);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $name = $form->get('name')->getData();
            $event = $form->get('event')->getData();
            $sponsor = $form->get('sponsor')->getData();
            $seats = $form->get('seats')->getData();

            $reservationData = [];

            if ($seats) {
                foreach ($seats as $seat) {
                    $reservation = $this->reservationManager->newInstance($email, $seat, $event, $sponsor, $name);

                    $reservationData[] = $this->reservationManager->handleCreation($reservation);
                }
            } else {
                $reservationData[] = $this->reservationManager->handleCreation($reservation);
            }

            $this->em->flush();

            $this->mailer->sendEmail($reservation->getEmail(), $reservationData);
        }

        return new JsonResponse([
            'data' => []
        ]);
    }

    public function showReservationSuccess(): Response
    {
        return $this->render('@Seating/Reservation/public/showReservationSuccess.html.twig');
    }

    public function provideQrCodeAction($uuid, Request $request, UploaderHelper $uploaderHelper): Response
    {
        $reservation = $this->em->getRepository(Reservation::class)->findOneBy(['uuid' => $uuid]);

        $qrCodePath = $uploaderHelper->asset($reservation, 'qrCodeFile');

        $privateDir = $this->params->get('private_files_dir');

        $fullPath = $privateDir . $qrCodePath;

        return new BinaryFileResponse($fullPath);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request, PaginatorInterface $paginator): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@Seating/Reservation/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Reservation::class);
        $qb = $repo->createQb();

        $keyword = $request->query->get('keyword');

        if ($keyword) {
            $repo->addWhereKeyword($qb, $keyword);
        }

        $sort = $request->query->get('sort');
        if ($sort) {
            $allowedSorts = ['id', 'updatedAt'];

            if (in_array($sort, $allowedSorts)) {
                $request->query->set('sort', Reservation::ENTITY_ALIAS . '.' . $sort);
            } else {
                $request->query->remove('sort');
            }
        }

        $page = max($request->query->get('page', 1), 1);
        $limit = $request->query->get('limit', 8);

        $pagination = $paginator->paginate($qb, $page, $limit);

        $reservations = $pagination->getItems();

        return new JsonResponse([
            'pagination' => $this->serializer->normalize($pagination),
            'data' => $this->serializer->normalize($reservations, null, [
                AbstractNormalizer::GROUPS => Reservation::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_RESERVATION_VALIDATOR')]
    public function confirmAdminAction($uuid, Request $request): Response
    {
        $reservation = $this->em->getRepository(Reservation::class)->findOneBy(['uuid' => $uuid]);

        if (!$reservation) {
            return $this->render('@Seating/Reservation/admin/confirm/error.html.twig', [
                'message' => "Rezervarea nu exista in baza de date"
            ]);
        }

        if ($reservation->getClaimedAt()) {
            return $this->render('@Seating/Reservation/admin/confirm/error.html.twig', [
                'message' => "Rezervarea a fost deja revendicata"
            ]);
        }

        $reservation
            ->setClaimedAt(new \DateTimeImmutable())
            ->setClaimedBy($this->getUser());

        $this->em->flush();

        return $this->render('@Seating/Reservation/admin/confirm/success.html.twig', [
            'message' => "Rezervarea a fost revendicata cu succes!",
            'reservation' => $reservation,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function showAdminAction($id, $seatId, $eventId, Request $request): Response
    {
        if ($id === '-' && ($seatId === '-' || $eventId === '-')) {
            throw new BadRequestHttpException('Invalid params provided');
        }

        if ($id === '-') {
            $event = $this->es->findOrReject(Event::class, $eventId);
            $seat = $this->es->findOrReject(Seat::class, $seatId);

            $searchParams = [
                'seat' => $seat,
                'event' => $event
            ];

            $normalizerGroups = ['reservation.details', 'user.details', 'timestampable'];
        } else {
            $searchParams = ['id' => $id];

            $normalizerGroups = Reservation::NORMALIZER_GROUPS;
        }

        $reservation = $this->em->getRepository(Reservation::class)->findOneBy($searchParams);

        return new JsonResponse([
            'data' => $this->serializer->normalize($reservation, null, [
                AbstractNormalizer::GROUPS => $normalizerGroups,
            ]),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listStatisticsAdminAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            $events = $this->em->getRepository(Event::class)->findAll();

            return $this->render('@Seating/Reservation/admin/listStatistics.html.twig', [
                'events' => $this->serializer->normalize($events, null, [
                    AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
                ])
            ]);
        }

        $repo = $this->em->getRepository(Reservation::class);

        $eventId = $request->query->get('eventId');

        if ($eventId) {
            $event = $this->em->getRepository(Event::class)->find($eventId);
            $reservations = $repo->findBy(['event' => $event]);
        } else {
            $reservations = $repo->findAll();
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($reservations, null, [
                AbstractNormalizer::GROUPS => Reservation::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function deleteAdminAction($id, Request $request): Response
    {
        $reservation = $this->es->findOrReject(Reservation::class, $id);

        $this->es->delete($reservation);

        return new JsonResponse([
            'data' => []
        ]);
    }
}
