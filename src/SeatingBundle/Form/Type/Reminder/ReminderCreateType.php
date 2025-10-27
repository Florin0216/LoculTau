<?php

namespace SeatingBundle\Form\Type\Reminder;

use SeatingBundle\Entity\Reminder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReminderCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event')
            ->add('scheduledAt')
            ->add('status')
            ->add('email');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reminder::class,
            'csrf_protection' => false,
        ]);

    }

}
