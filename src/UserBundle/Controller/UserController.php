<?php

namespace UserBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use UserBundle\Entity\User;
use UserBundle\Form\Factory\UserFormFactory;
use UserBundle\Service\UserManager;

class UserController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected EntityManagerInterface $em,
        protected UserFormFactory $formFactory,
        protected UserManager $userManager,
        protected EntityService $es,
        protected UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function showAction(Request $request): Response
    {
        return new JsonResponse([
            'data' => $this->serializer->normalize($this->getUser(), null, [
                'groups' => User::NORMALIZER_GROUPS,
            ])
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request, PaginatorInterface $paginator): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@User/User/admin/list.html.twig');
        }

        $repo = $this->em->getRepository(User::class);
        $qb = $repo->createQb();

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 20);

        $pagination = $paginator->paginate($qb, $page, $limit);

        $users = $pagination->getItems();

        return new JsonResponse([
            'pagination' => $this->serializer->normalize($pagination),
            'data' => $this->serializer->normalize($users, null, [
                AbstractNormalizer::GROUPS => User::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function newSuperAdminAction(Request $request): Response
    {
        $user = $this->userManager->newInstance();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($user);
        $form->submit($payload->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            $hash = $this->passwordHasher->hashPassword($user, $password);

            $user->setPassword($hash);

            $this->es->save($user);
        } else {
            throw new FormInvalidDataException($form);
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($user, null, [
                AbstractNormalizer::GROUPS => User::NORMALIZER_GROUPS,
            ])
        ]);
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function editSuperAdminAction($id, Request $request): Response
    {
        $user = $this->es->findOrReject(User::class, $id);

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getEditForm($user);
        $form->submit($payload->getData(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('password') && $form->get('password')->getData()) {
                $password = $form->get('password')->getData();

                $hash = $this->passwordHasher->hashPassword($user, $password);

                $user->setPassword($hash);
            }

            $this->em->flush();
        } else {
            throw new FormInvalidDataException($form);
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($user, null, [
                AbstractNormalizer::GROUPS => User::NORMALIZER_GROUPS,
            ])
        ]);
    }
}
