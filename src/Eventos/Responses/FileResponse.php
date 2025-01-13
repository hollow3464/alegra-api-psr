<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\File;

final class FileResponse
{
    public function __construct(
        public readonly File $file
    ) {
    }
}
