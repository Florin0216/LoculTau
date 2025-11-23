<?php

namespace SeatingBundle\Form\Type\Sponsor;

use SeatingBundle\Entity\Sponsor;
use SeatingBundle\Form\Type\Event\Base64Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SponsorCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('color')
            ->add('imageFile', Base64Type::class, [
                'required' => false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsor::class,
            'csrf_protection' => false,
        ]);
    }

}
