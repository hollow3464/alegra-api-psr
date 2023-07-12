<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AlegraError;

final class DuplicateEventResponse implements FailedRequestResponse
{
    public function __construct(
    ) {
    }

    /** @return array<AlegraError> */
    public function getErrors(): array
    {
        return [];
    }
}
