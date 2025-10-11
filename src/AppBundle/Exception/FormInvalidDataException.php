<?php

namespace AppBundle\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormInvalidDataException extends HttpException
{
	public function __construct(
		protected FormInterface $form,
	)
	{
        $message = '';

        foreach ($form->getErrors(true) as $error) {
            $message .= '• ' . $error->getMessage() . PHP_EOL;
        }

		parent::__construct(statusCode: 400, message: $message);
	}

	/**
	 * @return FormInterface
	 */
	public function getForm(): FormInterface
	{
		return $this->form;
	}
}
