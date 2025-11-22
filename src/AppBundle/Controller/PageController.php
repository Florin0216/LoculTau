<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Form\Factory\PageFormFactory;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PageController extends AbstractController
{
    public function __construct(
        protected EntityService $entityService,
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer,
        protected PageFormFactory $formFactory
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request):Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@App/Page/admin/list.html.twig');
        }

        $repo = $this->entityManager->getRepository(Page::class);
        $qb = $repo->createQb();

        $pages = $qb->getQuery()->getResult();

        return new JsonResponse([
            'data' => $this->serializer->normalize($pages, null, [
                AbstractNormalizer::GROUPS => Page::NORMALIZER_GROUPS,
            ])
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $page = new Page();

        $payload = json_decode($request->getContent(), true);

        $form = $this->formFactory->getCreateForm($page);
        $form->submit($payload['data'] ?? []);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->entityService->save($page);

        return new JsonResponse([
            'data' => $this->serializer->normalize($page, null, [
                AbstractNormalizer::GROUPS => Page::NORMALIZER_GROUPS,
            ])
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    public function editAdminAction($id, Request $request): Response
    {
        $page = $this->entityService->findOrReject(Page::class, $id);

        $payload = json_decode($request->getContent(), true);

        $form = $this->formFactory->getEditForm($page);
        $form->submit($payload['data'] ?? []);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'data' => $this->serializer->normalize($page, null, [
                AbstractNormalizer::GROUPS => Page::NORMALIZER_GROUPS,
            ])
        ]);
    }

}
