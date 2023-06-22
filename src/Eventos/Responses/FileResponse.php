<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\File;

final class FileResponse
{
    public function __construct(
        public readonly File $file
    ) {
    }
}
