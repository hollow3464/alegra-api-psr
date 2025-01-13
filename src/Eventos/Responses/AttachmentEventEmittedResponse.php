<?php

namespace Hollow3464\Alegra\Eventos\Responses;

use Hollow3464\Alegra\Eventos\DataTypes\AttachmentEmmitedEvent;

final class AttachmentEventEmittedResponse
{
    public function __construct(
        public readonly AttachmentEmmitedEvent $event
    ) {
    }
}
