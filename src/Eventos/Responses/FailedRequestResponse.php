<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\AlegraError;

interface FailedRequestResponse
{
    /** @return array<AlegraError|string> */
    public function getErrors(): array;
}
