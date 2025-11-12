<?php

namespace SeatingBundle\Form\Type\Event;

use SeatingBundle\Entity\Event;
use SeatingBundle\Entity\Sponsor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCreateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('date')
            ->add('room')
            ->add('imageFile', Base64Type::class, [
                'required' => false
            ])
            ->add('sponsors', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Sponsor::class,
                ],
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'csrf_protection' => false,
        ]);
    }
}
