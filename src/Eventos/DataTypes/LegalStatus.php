<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

enum LegalStatus: string
{
    case ACCEPTED = 'ACCEPTED';
    case ACCEPTED_WITH_OBSERVATIONS = 'ACCEPTED_WITH_OBSERVATIONS';
    case REJECTED = 'REJECTED';
}
