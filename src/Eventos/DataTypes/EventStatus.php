<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

enum EventStatus: string
{
    case REGISTERED = 'REGISTERED';
    case WAITING_RESPONSE = 'WAITING_RESPONSE';
    case FAILED = 'FAILED';
    case SENT = 'SENT';
}
