<?php

namespace Vinorcola\ApiServerTools;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidInputException extends BadRequestHttpException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * InvalidInputException constructor.
     *
     * @param string                           $message
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct(string $message, ConstraintViolationListInterface $errors)
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

    /**
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->errors as $error) {
            /** @var ConstraintViolationInterface $error */
            $messages[] = $error->getMessage();
        }

        return $messages;
    }
}
