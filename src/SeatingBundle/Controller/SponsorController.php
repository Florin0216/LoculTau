<?php

namespace SeatingBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use AppBundle\Services\ThumbnailService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Sponsor;
use SeatingBundle\Form\Factory\SponsorFormFactory;
use SeatingBundle\Service\SponsorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SponsorController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected EntityService          $es,
        protected SerializerInterface $serializer,
        protected SponsorManager $sponsorManager,
        protected SponsorFormFactory $formFactory,
        protected ThumbnailService $thumbnailService,
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    public function eventSponsorsListAdminAction($id, Request $request): Response
    {
        $event = $this->es->findOrReject(Event::class,$id);
        $sponsors = $event->getSponsors();

        return new JsonResponse([
            'data' => $this->serializer->normalize($sponsors, null, [
                AbstractNormalizer::GROUPS => Sponsor::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@Seating/Sponsor/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(Sponsor::class);
        $qb = $repo->createQb();

        $sponsors = $qb->getQuery()->getResult();

        return new JsonResponse([
            'data' => $this->serializer->normalize($sponsors, null, [
                AbstractNormalizer::GROUPS => Sponsor::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $sponsor = $this->sponsorManager->newInstance();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($sponsor);

        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        if ($sponsor->getImageFile()) {
            $thumbnailFile = $this->thumbnailService->generateThumbnail($sponsor->getImageFile(), 800, 800);
            $sponsor->setImageFile($thumbnailFile);
        }

        $this->es->save($sponsor);

        return new JsonResponse([
            'data' => $this->serializer->normalize($sponsor, null, [
                AbstractNormalizer::GROUPS => Sponsor::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function editAdminAction($id, Request $request): Response
    {
        $sponsor = $this->es->findOrReject(Sponsor::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($sponsor);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        if ($sponsor->getImageFile()) {
            $thumbnailFile = $this->thumbnailService->generateThumbnail($sponsor->getImageFile(), 800, 800);
            $sponsor->setImageFile($thumbnailFile);
        }

        $this->em->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($sponsor, null, [
                AbstractNormalizer::GROUPS => Sponsor::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function deleteAdminAction($id): Response
    {
        $sponsor = $this->es->findOrReject(Sponsor::class, $id);

        $this->es->delete($sponsor);

        return new JsonResponse([
            'data' => []
        ]);

    }

}
