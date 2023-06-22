<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

enum RegimeCode: string
{
    case GRAN_CONTRIBUYENTE = "O-13";
    case AUTORETENEDOR = "O-15";
    case AGENTE_RETENCION_IVA = "O-23";
    case REGIMEN_SIMPLE = "O-47";
    case OTROS = "R-99-PN";
}
