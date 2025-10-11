<?php

namespace SeatingBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Exception\ValidationException;
use Knp\Component\Pager\PaginatorInterface;
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
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        protected ParameterBagInterface $params,
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

            $seats = $form->get('seats')->getData();

            $reservationData = [];

            if ($seats) {
                foreach ($seats as $seat) {
                    $reservation = $this->reservationManager->newInstance($email, $seat, $event, $name);

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

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request, PaginatorInterface $paginator): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@Seating/Reservation/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Reservation::class);
        $qb = $repo->createQb();

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 20);

        $pagination = $paginator->paginate($qb, $page, $limit);

        $reservations = $pagination->getItems();

        return new JsonResponse([
            'pagination' => $this->serializer->normalize($pagination),
            'data' => $this->serializer->normalize($reservations, null, [
                AbstractNormalizer::GROUPS => Reservation::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
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

        $reservation->setClaimedAt(new \DateTimeImmutable());

        $this->em->flush();

        $seat = $this->em->getRepository(Seat::class)->find(['id' => $reservation->getSeat()]);

        return $this->render('@Seating/Reservation/admin/confirm/success.html.twig', [
            'message' => "Rezervarea a fost revendicata cu succes!",
            'reservation' => $reservation,
            'seat' => $seat,
        ]);
    }

    public function provideQrCodeAction($uuid, Request $request, UploaderHelper $uploaderHelper): Response
    {
        $reservation = $this->em->getRepository(Reservation::class)->findOneBy(['uuid' => $uuid]);

        $qrCodePath = $uploaderHelper->asset($reservation, 'qrCodeFile');

        $privateDir = $this->params->get('private_files_dir');

        $fullPath = $privateDir . $qrCodePath;

        return new BinaryFileResponse($fullPath);
    }
}
