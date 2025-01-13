<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\AlegraError;

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
