<?php

namespace SeatingBundle\Form\Type;

use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('event')
            ->add('seat')
            ->add('seats', CollectionType::class, [
                'mapped' => false,
                'required' => false,
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Seat::class,
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'csrf_protection' => false,
        ]);
    }

}
