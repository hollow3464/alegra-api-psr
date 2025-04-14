<?php

namespace Hollow3464\Alegra\Exceptions;

use CuyZ\Valinor\Mapper\MappingError;

final class UnhandledResponseMappingException extends HandlerException
{
    public function __construct(
        public readonly mixed $responseBody,
        public readonly int $statusCode,
        public readonly MappingError $mappingError
    ) {
        parent::__construct("Unhandled response", 1);
    }
}
