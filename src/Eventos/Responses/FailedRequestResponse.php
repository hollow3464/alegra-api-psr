<?php

namespace SaveColombia\AllegraApiPsr\Eventos\Responses;

use SaveColombia\AllegraApiPsr\Eventos\DataTypes\Error;

interface FailedRequestResponse
{
    /** @return array<Error> */
    public function getErrors(): array;
}
