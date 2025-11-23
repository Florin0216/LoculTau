<?php

namespace SeatingBundle\Form\Type\Seat;

use SeatingBundle\Entity\Seat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeatCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rowNo')
            ->add('number')
            ->add('section')
            ->add('room')
            ->add('sponsor');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults([
           'data_class' => Seat::class,
           'csrf_protection' => false
       ]);
    }
}
