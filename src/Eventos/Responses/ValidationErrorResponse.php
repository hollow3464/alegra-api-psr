<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\AlegraError;

final class ValidationErrorResponse implements FailedRequestResponse
{
    /**
     * @param array<AlegraError> $errors
     */
    public function __construct(
        private readonly array $errors = []
    ) {
    }

    /** @return array<AlegraError> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
