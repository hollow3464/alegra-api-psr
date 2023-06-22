<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Error;

interface FailedRequestResponse
{
    /** @return array<Error> */
    public function getErrors(): array;
}
