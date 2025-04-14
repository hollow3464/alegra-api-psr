<?php

namespace Hollow3464\Alegra\Exceptions;

final class UnhandledResponseException extends HandlerException
{
    public function __construct(
        public readonly mixed $responseBody,
        public readonly int $statusCode,
    ) {
        parent::__construct("Unhandled response", 1);
    }
}
