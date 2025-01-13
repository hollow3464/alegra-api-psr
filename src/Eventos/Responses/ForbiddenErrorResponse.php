<?php

namespace Hollow3464\Alegra\Eventos\Responses;

final class ForbiddenErrorResponse implements FailedRequestResponse
{
    /**
     * @param array<string> $errors
     */
    public function __construct(
        private readonly array $errors = [],
    ) {
    }

    /** @return array<string> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
