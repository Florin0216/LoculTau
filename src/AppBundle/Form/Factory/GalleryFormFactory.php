<?php

namespace AppBundle\Form\Factory;

use AppBundle\Entity\Gallery;
use AppBundle\Form\Type\Gallery\GalleryCreateType;
use AppBundle\Form\Type\Gallery\GalleryEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class GalleryFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Gallery $gallery, array $options = []): FormInterface
    {
        return $this->formFactory->create(GalleryCreateType::class, $gallery, $options);
    }

    public function getEditForm(Gallery $gallery, array $options = []): FormInterface
    {
        return $this->formFactory->create(GalleryEditType::class, $gallery, $options);
    }

}
