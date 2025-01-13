<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

enum TaxCode: string
{
    case IVA = "01";
    case INC = "04";
    case IVA_INC = "ZA";
    case NO_APLICA = "ZZ";
}
