<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

final class Error
{
    public function __construct(
        public readonly string $code,
        public readonly string $message
    ) {
    }
}
