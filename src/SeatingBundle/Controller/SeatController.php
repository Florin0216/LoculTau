<?php

namespace SeatingBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Room;
use SeatingBundle\Entity\Seat;
use SeatingBundle\Form\Factory\SeatFormFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SeatController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected EntityService $es,
        protected SerializerInterface $serializer,
        protected SeatFormFactory $formFactory
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function listAction($roomId, $eventId, Request $request): JsonResponse
    {
        $room = $this->es->findOrReject(Room::class, $roomId);

        $event = $this->es->findOrReject(Event::class, $eventId);

        $seats = $this->em->getRepository(Seat::class)->findBy(['room' => $room]);

        $defaultListing = $request->get('normalListing', false);

        if (!$defaultListing) {
            $responseData = [];
            foreach ($seats as $seat) {
                $responseData[$seat->getSection()][$seat->getRowNo()][$seat->getNumber()] = $seat;
            }
        } else {
            $responseData = $seats;
        }

        $response = $this->serializer->normalize($responseData, null, [
            AbstractNormalizer::GROUPS => Seat::NORMALIZER_GROUPS,
            'event' => $event,
        ]);

        return new JsonResponse($response);
    }

    /**
     * @throws \Exception
     */
    public function editAdminAction($id, Request $request): Response
    {
        $seat = $this->es->findOrReject(Seat::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($seat);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->em->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($seat, null, [
                AbstractNormalizer::GROUPS => Seat::NORMALIZER_GROUPS,
            ])
        ]);
    }
}
