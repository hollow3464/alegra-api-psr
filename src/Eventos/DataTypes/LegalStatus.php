<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

enum LegalStatus: string
{
    case ACCEPTED = 'ACCEPTED';
    case ACCEPTED_WITH_OBSERVATIONS = 'ACCEPTED_WITH_OBSERVATIONS';
    case REJECTED = 'REJECTED';
}
