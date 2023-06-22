<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Error;

final class ResourceNotFoundResponse implements FailedRequestResponse
{
    /**
     * @param array<Error> $errors
     */
    public function __construct(
        private readonly array $errors = []
    ) {
    }

    /** @return array<Error> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
