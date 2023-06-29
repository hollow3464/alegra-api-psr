<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

final class EventCompany
{
    public function __construct(
        public readonly string $id
    ) {
    }
}
