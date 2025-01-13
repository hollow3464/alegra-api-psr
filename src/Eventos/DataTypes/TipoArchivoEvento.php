<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

enum TipoArchivoEvento: string
{
    case XML = 'XML';
    case ATTACHED_DOCUMENT = 'ATTACHED_DOCUMENT';
    case GOVERNMENT_RESPONSE = 'GOVERNMENT_RESPONSE';
}
