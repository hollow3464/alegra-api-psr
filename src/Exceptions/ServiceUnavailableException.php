<?php

namespace SaveColombia\AlegraApiPsr\Exceptions;

final class ServiceUnavailableException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Servicio no disponible");
    }
}
