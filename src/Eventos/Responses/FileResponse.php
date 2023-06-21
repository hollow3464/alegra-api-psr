<?php

namespace SaveColombia\AllegraApiPsr\Eventos\Responses;

use SaveColombia\AllegraApiPsr\Eventos\DataTypes\File;

final class FileResponse
{
    public function __construct(
        public readonly File $file
    ) {
    }
}
