<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\EmmitedEvent;

final class EventEmittedResponse
{
    public function __construct(
        public readonly EmmitedEvent $event
    ) {
    }
}
