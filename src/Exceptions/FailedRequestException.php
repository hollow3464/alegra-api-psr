<?php

namespace Hollow3464\Alegra\Exceptions;

use Hollow3464\Alegra\Eventos\Responses\FailedRequestResponse;

final class FailedRequestException extends HandlerException
{
    public function __construct(
        public readonly FailedRequestResponse $response,
        public readonly mixed $responseBody,
        public readonly int $statusCode,
        public readonly string $endpoint,
    ) {
        parent::__construct("Ha fallado el envío del evento");
    }
}
