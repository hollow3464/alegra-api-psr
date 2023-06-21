<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

final class EventListEvent
{
    public function __construct(
        public readonly string $responseCode,
        public readonly EventType $eventType,
        public readonly string $effectiveTime,
        public readonly DocumentReference $documentReference,
        public readonly IssuerParty $issuerParty,
        public readonly ReceiverParty $receiverParty,
        public readonly string $xmlBase64
    ) {
    }
}
