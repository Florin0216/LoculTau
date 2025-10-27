<?php

namespace AppBundle\Form\Factory;

use AppBundle\Entity\GalleryItem;
use AppBundle\Form\Type\GalleryItem\GalleryItemCreateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class GalleryItemFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(GalleryItem $galleryItem, array $options = []): FormInterface
    {
        return $this->formFactory->create(GalleryItemCreateType::class, $galleryItem, $options);
    }

}
