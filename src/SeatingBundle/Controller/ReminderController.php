<?php

namespace SeatingBundle\Controller;

use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Reminder;
use SeatingBundle\Form\Factory\ReminderFormFactory;
use SeatingBundle\Service\MailerService;
use SeatingBundle\Service\ReminderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ReminderController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ReminderFormFactory    $formFactory,
        protected EntityService          $es,
        protected ReminderManager        $reminderManager,
        protected SerializerInterface    $serializer,
        protected MailerService          $ms
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    public function remindersListAction($id, Request $request): Response
    {
        $reminders = $this->em->getRepository(Reminder::class)->findBy(['event' => $id]);

        return new JsonResponse([
            'data' => $this->serializer->normalize($reminders, null, [
                AbstractNormalizer::GROUPS => Reminder::NORMALIZER_GROUPS,
            ])
        ]);

    }

    /**
     * @throws TransportExceptionInterface
     */
    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request): Response
    {
        $reminder = new Reminder();

        $form = $this->formFactory->getCreateForm($reminder);
        $form->submit(json_decode($request->getContent(), true));

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $scheduledAt = $form->get('scheduledAt')->getData();
            $event = $form->get('event')->getData();
            $status = $form->get('status')->getData();

            $reminder = $this->reminderManager->newInstance($scheduledAt, $event, $status, $email);

            if ($scheduledAt <= new \DateTimeImmutable()) {
                $this->ms->sendReminderEmail($reminder->getEmail(), $reminder->getEvent());
                $reminder->setStatus('sent');
            }

            $this->em->persist($reminder);
            $this->em->flush();
        }

        return new JsonResponse([
            'data' => []
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    public function deleteAdminAction($id): Response
    {
        $reminder = $this->es->findOrReject(Reminder::class, $id);

        $this->es->delete($reminder);

        return new JsonResponse([
            'data' => []
        ]);

    }

}
