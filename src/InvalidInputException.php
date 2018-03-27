<?php

namespace Vinorcola\ApiServerTools;

use stdClass;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
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
        $errorMessages = [];
        foreach ($this->errors as $error) {
            /** @var ConstraintViolationInterface $error */
            if (!key_exists($error->getPropertyPath(), $errorMessages)) {
                $errorMessages[$error->getPropertyPath()] = [];
            }
            $errorMessages[$error->getPropertyPath()][] = [
                'message' => $error->getMessage(),
                'key'     => $error->getMessageTemplate(),
                'params'  => $this->cleanParameters($error->getParameters()),
            ];
        }

        return $errorMessages;
    }

    /**
     * Clean the parameters and return them as an object to force {} when encoding as JSON.
     *
     * @param array $parameters
     * @return stdClass
     */
    private function cleanParameters(array $parameters): stdClass
    {
        $result = new stdClass();
        foreach ($parameters as $key => $parameter) {
            if ($key === '{{ value }}') {
                continue;
            }
            if (preg_match('/^\{\{ (.+) \}\}$/', $key, $matches)) {
                $key = $matches[1];
            }
            $result->$key = $parameter;
        }

        return $result;
    }
}
