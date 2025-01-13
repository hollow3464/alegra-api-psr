<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

final class AlegraError
{
    public function __construct(
        public readonly string $message,
        public readonly ?string $code = null,
    ) {
    }
}
