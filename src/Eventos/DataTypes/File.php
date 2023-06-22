<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

final class File
{
    public function __construct(public readonly string $content)
    {
    }
}
