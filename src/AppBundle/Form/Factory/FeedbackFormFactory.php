<?php

namespace AppBundle\Form\Factory;

use AppBundle\Entity\Feedback;
use AppBundle\Form\Type\Feedback\FeedbackCreateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FeedbackFormFactory
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    public function getCreateForm(Feedback $feedback, array $options = []): FormInterface
    {
        return $this->formFactory->create(FeedbackCreateType::class, $feedback, $options);
    }

}
