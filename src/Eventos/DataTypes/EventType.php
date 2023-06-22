<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

final class EventType
{
    public function __construct(
        public readonly string $code,
        public readonly string $value
    ) {
    }
}
