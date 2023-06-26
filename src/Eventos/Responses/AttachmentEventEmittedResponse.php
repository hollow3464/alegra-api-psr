<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AttachmentEmmitedEvent;

final class AttachmentEventEmittedResponse
{
    public function __construct(
        public readonly AttachmentEmmitedEvent $event
    ) {
    }
}
