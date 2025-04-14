<?php

namespace Hollow3464\Alegra\Exceptions;

final class RejectedEventException extends HandlerException
{
    public function __construct(
        public readonly mixed $response,
        public readonly mixed $responseBody,
        public readonly int $statusCode
    ) {
        parent::__construct("El evento ya fue emitido");
    }
}
