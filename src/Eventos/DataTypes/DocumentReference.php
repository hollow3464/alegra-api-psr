<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

final class DocumentReference
{
    public function __construct(
        public readonly string $number,
        public readonly string $uuid,
    ) {
    }
}
