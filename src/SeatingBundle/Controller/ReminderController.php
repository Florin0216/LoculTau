<?php

namespace SeatingBundle\Controller;

use AppBundle\Services\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use SeatingBundle\Entity\Reminder;
use SeatingBundle\Form\Factory\ReminderFormFactory;
use SeatingBundle\Message\ReminderMessage;
use SeatingBundle\Service\ReminderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReminderController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ReminderFormFactory    $formFactory,
        protected EntityService          $es,
        protected ReminderManager        $reminderManager
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    public function newAdminAction(Request $request, MessageBusInterface $bus): Response
    {
        $reminder = new Reminder();

        $form = $this->formFactory->getCreateForm($reminder);
        $form->submit(json_decode($request->getContent(), true));

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $scheduledAt = $form->get('scheduledAt')->getData();
            $event = $form->get('event')->getData();
            $status = $form->get('status')->getData();


            $reminder = $this->reminderManager->newInstance($scheduledAt->setTimezone(new \DateTimeZone('Europe/Bucharest')), $event, $status, $email);
            $this->em->persist($reminder);
            $this->em->flush();

            if ($scheduledAt <= new \DateTimeImmutable()) {
                $bus->dispatch(new ReminderMessage($reminder->getId()));
            }
        }

        return new JsonResponse([
            'data' => []
        ]);

    }

}
