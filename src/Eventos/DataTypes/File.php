<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

final class File
{
    public function __construct(public readonly string $content)
    {
    }
}
