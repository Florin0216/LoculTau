<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GalleryItem;
use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Form\Factory\GalleryItemFormFactory;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use AppBundle\Services\ThumbnailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GalleryItemController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected EntityService          $entityService,
        protected SerializerInterface    $serializer,
        protected GalleryItemFormFactory $formFactory,
        protected ThumbnailService     $thumbnailService,
    )
    {
    }

    public function listAction($id, Request $request): Response
    {
        $galleryItems = $this->em->getRepository(GalleryItem::class)->findBy(['gallery' => $id]);

        return new JsonResponse(
            $this->serializer->normalize($galleryItems, null, [
                AbstractNormalizer::GROUPS => GalleryItem::NORMALIZER_GROUPS,
            ])
        );
    }

    /**
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction($id, Request $request): Response
    {
        $galleryItem = new GalleryItem();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($galleryItem);
        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        if ($galleryItem->getImageFile()) {
            $thumbnailFile = $this->thumbnailService->generateThumbnail($galleryItem->getImageFile(), 800, 800);
            $galleryItem->setThumbnailFile($thumbnailFile);
        }

        $this->entityService->save($galleryItem);

        return new JsonResponse([
            'data' => $this->serializer->normalize($galleryItem, null, [
                AbstractNormalizer::GROUPS => GalleryItem::NORMALIZER_GROUPS,
            ])
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    public function deleteAdminAction($id, $itemId): Response
    {
        $galleryItem = $this->entityService->findOrReject(GalleryItem::class, $itemId);

        $this->entityService->delete($galleryItem);

        return new JsonResponse([
            'data' => []
        ]);

    }

}
