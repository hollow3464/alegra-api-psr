<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

final class AlegraError
{
    public function __construct(
        public readonly string $message,
        public readonly ?string $code = null,
    ) {
    }
}
