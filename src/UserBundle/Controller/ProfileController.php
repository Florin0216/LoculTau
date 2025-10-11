<?php

namespace UserBundle\Controller;

use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Utils\JsonRequestPayload;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use UserBundle\Entity\User;
use UserBundle\Form\Factory\ProfileFormFactory;

class ProfileController extends AbstractController
{

    public function __construct(
        protected ProfileFormFactory $formFactory,
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[IsGranted('ROLE_USER')]
    public function editAction(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->formFactory->getEditForm($user);
        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form->submit($payload->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
        } else {
            throw new FormInvalidDataException($form);
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($user, null, [
                'groups' => User::NORMALIZER_GROUPS,
            ])
        ]);
    }
}
