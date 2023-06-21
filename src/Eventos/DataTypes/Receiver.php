<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

final class Receiver
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $identificationType,
        public readonly string $dv,
    ) {
    }
}
