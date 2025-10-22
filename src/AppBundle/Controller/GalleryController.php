<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gallery;
use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Form\Factory\GalleryFormFactory;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GalleryController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface    $serializer,
        protected EntityService          $entityService,
        protected GalleryFormFactory     $formFactory,
    )
    {
    }

    public function listAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            $events = $this->em->getRepository(Event::class)->findAll();

            return $this->render('@App/Gallery/public/list.html.twig', [
                'jsData' => [
                    'events' => $this->serializer->normalize($events, null, [
                        AbstractNormalizer::GROUPS => Event::NORMALIZER_GROUPS,
                    ])
                ]
            ]);
        }

        $eventId = $request->query->get('eventId');

        if ($eventId) {
            $event = $this->em->getRepository(Event::class)->find($eventId);
            $gallery = $this->em->getRepository(Gallery::class)->findOneBy([
                'event' => $event,
                'isGeneral' => false
            ]);
        } else {
            $gallery = $this->em->getRepository(Gallery::class)->findOneBy(['isGeneral' => true]);
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($gallery, null, [
                AbstractNormalizer::GROUPS => Gallery::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@App/Gallery/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Gallery::class);
        $qb = $repo->createQb();

        $galleries = $qb->getQuery()->getResult();

        return new JsonResponse([
            'data' => $this->serializer->normalize($galleries, null, [
                AbstractNormalizer::GROUPS => Gallery::NORMALIZER_GROUPS,
            ])
        ]);

    }

    /**
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $gallery = new Gallery();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($gallery);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->entityService->save($gallery);

        return new JsonResponse([
            'data' => $this->serializer->normalize($gallery, null, [
                AbstractNormalizer::GROUPS => Gallery::NORMALIZER_GROUPS,
            ])
        ]);

    }

    /**
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function editAdminAction($id, Request $request): Response
    {
        $gallery = $this->entityService->findOrReject(Gallery::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($gallery);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->em->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($gallery, null, [
                AbstractNormalizer::GROUPS => Gallery::NORMALIZER_GROUPS,
            ])
        ]);

    }


}
