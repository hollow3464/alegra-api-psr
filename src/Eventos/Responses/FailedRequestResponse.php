<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AlegraError;

interface FailedRequestResponse
{
    /** @return array<AlegraError> */
    public function getErrors(): array;
}
