<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

enum IdentificationType: string
{
    case REGISTRO_CIVIL = "11";
    case TARJETA_IDENTIDAD = "12";
    case CEDULA_CIUDADANIA = "13";
    case TARJETA_EXTRANJERIA = "21";
    case CEDULA_EXTRANJERIA = "22";
    case NIT = "31";
    case PASAPORTE = "41";
    case DOCUMENTO_EXTRANJERO = "42";
    case PEP = "47";
    case NIT_OTRO_PAIS = "50";
    case NUIT = "91";
}
