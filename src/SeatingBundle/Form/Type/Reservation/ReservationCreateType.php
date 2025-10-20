<?php

namespace SeatingBundle\Form\Type\Reservation;

use SeatingBundle\Entity\Reservation;
use SeatingBundle\Entity\Seat;
use SeatingBundle\Entity\Sponsor;
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
            ->add('sponsor', EntityType::class,[
                'class' => Sponsor::class,
                'required' => false,
            ])
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'csrf_protection' => false,
        ]);
    }

}
