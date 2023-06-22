<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

enum ClaimCode: string
{
    case DOCUMENTO_CON_INCONSISTENCIAS = "01";
    case MERCANCIA_NO_ENTREGADA_TOTALMENTE = "02";
    case MERCANCIA_NO_ENTREGADA_PARCIALMENTE = "03";
    case SERVICIO_NO_PRESTADO = "04";
}
