<?php

namespace AppBundle\Form\Factory;

use AppBundle\Entity\Page;
use AppBundle\Form\Type\Page\PageCreateType;
use AppBundle\Form\Type\Page\PageEditType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class PageFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Page $page, array $options = []): FormInterface
    {
        return $this->formFactory->create(PageCreateType::class, $page, $options);
    }

    public function getEditForm(Page $page, array $options = []): FormInterface
    {
        return $this->formFactory->create(PageEditType::class, $page, $options);
    }

}
