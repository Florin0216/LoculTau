<?php

namespace SeatingBundle\Form\Type\Event;

use SeatingBundle\Form\DataTransformer\Base64ToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Base64Type extends AbstractType
{
    public function __construct(
        protected Base64ToFileTransformer $transformer,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('base64')
            ->add('originalName');

        $builder->addModelTransformer($this->transformer);
    }

}
