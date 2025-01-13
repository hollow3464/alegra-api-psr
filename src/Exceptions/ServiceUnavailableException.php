<?php

namespace Hollow3464\Alegra\Exceptions;

final class ServiceUnavailableException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Servicio no disponible");
    }
}
