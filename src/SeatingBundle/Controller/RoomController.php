<?php

namespace SeatingBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Room;
use SeatingBundle\Entity\Seat;
use SeatingBundle\Form\Factory\RoomFormFactory;
use SeatingBundle\Service\RoomManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RoomController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected EntityService          $es,
        protected SerializerInterface    $serializer,
        protected RoomManager $roomManager,
        protected RoomFormFactory $formFactory,
    )
    {
    }

    public function showAction($eventId, $roomId, Request $request): Response
    {
        $room = $this->es->findOrReject(Room::class, $roomId);;

        $event = $this->es->findOrReject(Event::class, $eventId);

        return $this->render('@Seating/Room/public/show.html.twig',[
            'jsData' => [
                'event' => $this->serializer->normalize($event, null, [
                    AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
                ]),
                'room' => $this->serializer->normalize($room, null, [
                    AbstractNormalizer::GROUPS => Room::NORMALIZER_GROUPS,
                ])
            ]
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@Seating/Room/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Room::class);
        $qb = $repo->createQb();

        $rooms = $qb->getQuery()->getResult();

        return new JsonResponse([
            'data' => $this->serializer->normalize($rooms, null, [
                AbstractNormalizer::GROUPS => Room::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $room = $this->roomManager->newInstance();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($room);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->es->save($room);

        return new JsonResponse([
            'data' => $this->serializer->normalize($room, null, [
                AbstractNormalizer::GROUPS => Room::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function editAdminAction($id, Request $request): Response
    {
        $room = $this->es->findOrReject(Room::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($room);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->em->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($room, null, [
                AbstractNormalizer::GROUPS => Room::NORMALIZER_GROUPS,
            ])
        ]);
    }
}
