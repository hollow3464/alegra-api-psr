<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EmmitedEvent;

final class EventEmittedResponse
{
    public function __construct(
        public readonly EmmitedEvent $event
    ) {
    }
}
