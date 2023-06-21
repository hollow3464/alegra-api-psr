<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

use JsonSerializable;

final class AssociatedDocument implements JsonSerializable
{
    public function __construct(
        public readonly int $number,
        public readonly string $uuid,
        public readonly string $prefix = "",
    ) {
    }

    public function jsonSerialize(): mixed
    {
        $self = [
            'number' => $this->number,
            'uuid'   => $this->uuid,
        ];

        if ($this->prefix) {
            $self['prefix'] = $this->prefix;
        }

        return $self;
    }
}
