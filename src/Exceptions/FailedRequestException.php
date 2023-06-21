<?php

namespace SaveColombia\AllegraApiPsr\Exceptions;

use Exception;
use SaveColombia\AllegraApiPsr\Eventos\Responses\FailedRequestResponse;

final class FailedRequestException extends Exception
{
    public function __construct(private readonly FailedRequestResponse $response)
    {
        parent::__construct("Ha fallado el envío del evento");
    }

    public function getResponse(): FailedRequestResponse
    {
        return $this->response;
    }
}
