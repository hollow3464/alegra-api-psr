<?php

namespace SaveColombia\AllegraApiPsr\Eventos\Responses;

use SaveColombia\AllegraApiPsr\Eventos\DataTypes\Error;

final class ServerErrorResponse implements FailedRequestResponse
{
    /**
     * @param array<Error> $errors
     */
    public function __construct(
        private readonly array $errors = [],
    ) {
    }

    /** @return array<Error> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
