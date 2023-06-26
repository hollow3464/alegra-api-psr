<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AlegraError;

final class ServerCommunicationErrorResponse implements FailedRequestResponse
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
