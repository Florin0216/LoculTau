<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feedback;
use AppBundle\Exception\FormInvalidDataException;
use AppBundle\Form\Factory\FeedbackFormFactory;
use AppBundle\Helper\JsonRequestPayload;
use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FeedbackController extends AbstractController
{
    public function __construct(
        protected EntityService          $entityService,
        protected FeedbackFormFactory    $formFactory,
        protected SerializerInterface    $serializer,
        protected EntityManagerInterface $entityManager,
        protected HttpClientInterface $client,
        protected ParameterBagInterface  $params,
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    public function listAdminAction(Request $request): Response
    {
        $isHtmlRequest = $request->getRequestFormat() === 'html';

        if ($isHtmlRequest) {
            return $this->render('@App/Feedback/admin/list.html.twig');
        }

        $repo = $this->entityManager->getRepository(Feedback::class);
        $qb = $repo->createQb();

        $feedbacks = $qb->getQuery()->getResult();

        return new JsonResponse([
            'data' => $this->serializer->normalize($feedbacks, null, [
                AbstractNormalizer::GROUPS => Feedback::NORMALIZER_GROUPS,
            ])
        ]);

    }

    public function showAction(): Response
    {
        return $this->render('@App/Feedback/public/contact.html.twig');
    }


    /**
     * @throws \Exception
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function newAction(Request $request): Response
    {
        $feedback = new Feedback();

        $payload = JsonRequestPayload::newInstanceFromRequest($request);

        $form = $this->formFactory->getCreateForm($feedback);

        $form->submit($payload->getData());

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new FormInvalidDataException($form);
        }

        $recaptchaToken = $form->get('recaptchaToken')->getData();

        $response = $this->client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret'   => $this->params->get('recaptcha_secret_key'),
                'response' => $recaptchaToken,
            ],
        ]);

        $result = $response->toArray();

        if ($result['success'] && ($result['score'] ?? 0) > 0.5) {
            $this->entityService->save($feedback);
        }

        return new JsonResponse([
            'data' => $this->serializer->normalize($feedback, null, [
                AbstractNormalizer::GROUPS => Feedback::NORMALIZER_GROUPS,
            ])
        ]);
    }

}
