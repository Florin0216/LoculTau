<?php

namespace AppBundle\Form\Type\GalleryItem;

use AppBundle\Entity\GalleryItem;
use SeatingBundle\Form\Type\Event\Base64Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryItemCreateType extends AbstractType
{
    public function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('gallery')
            ->add('imageFile', Base64Type::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GalleryItem::class,
            'csrf_protection' => false,
        ]);
    }

}
