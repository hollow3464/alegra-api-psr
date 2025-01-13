<?php

namespace Hollow3464\Alegra\Exceptions;

use Exception;
use Hollow3464\Alegra\Eventos\Responses\FailedRequestResponse;

final class FailedRequestException extends Exception
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
