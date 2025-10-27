<?php

namespace SeatingBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use SeatingBundle\Entity\Event;
use SeatingBundle\Form\Factory\EventFormFactory;
use SeatingBundle\Service\EventManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class EventController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface    $serializer,
        protected EventFormFactory       $formFactory,
        protected EventManager           $eventManager,
        protected EntityService          $es,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function fetchEvents(SerializerInterface $serializer): JsonResponse
    {
        $events = $this->em->getRepository(Event::class)->findAll();

        $eventData = $this->serializer->normalize($events, null, [
            AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
        ]);

        return new JsonResponse($eventData);
    }

    public function listAction(): Response
    {

        return $this->render('@Seating/Event/public/displayEvents.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request, PaginatorInterface $paginator): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@Seating/Event/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Event::class);
        $qb = $repo->createQb();

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 20);

        $pagination = $paginator->paginate($qb, $page, $limit);

        $events = $pagination->getItems();

        return new JsonResponse([
            'data' => $this->serializer->normalize($events, null, [
                AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $event = new Event();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($event);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->es->save($event);

        return new JsonResponse([
            'data' => $this->serializer->normalize($event, null, [
                AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function editAdminAction($id, Request $request): Response
    {
        $event = $this->es->findOrReject(Event::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($event);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->em->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($event, null, [
                AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function deleteAdminAction($id): Response
    {
        $event = $this->es->findOrReject(Event::class, $id);

        $this->es->delete($event);

        return new JsonResponse([
            'data' => []
        ]);

    }
}
