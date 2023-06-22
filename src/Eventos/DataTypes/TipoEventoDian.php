<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

enum TipoEventoDian: string
{
    case RECIBO_FACTURA = "030";
    case RECLAMO_FACTURA = "031";
    case RECIBO_BIENES = "032";
    case ACEPTACION_EXPRESA = "033";
    case ACEPTACION_TACITA = "034";
}
