<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

final class DocumentReference
{
    public function __construct(
        public readonly ?string $number,
        public readonly ?string $uuid,
    ) {
    }
}
