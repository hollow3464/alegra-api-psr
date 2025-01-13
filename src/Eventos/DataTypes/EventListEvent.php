<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

/**
 * @property ?string $responseCode Código del evento
 * @property ?string $type Descripción del tipo de evento
 * @property ?string $effectiveTime Fecha y hora de emisión del evento
 */
final class EventListEvent
{
    public function __construct(
        public readonly ?string $responseCode,
        public readonly ?string $type,
        public readonly ?string $effectiveTime,
        public readonly ?DocumentReference $documentReference,
        public readonly ?IssuerParty $issuerParty,
        public readonly ?ReceiverParty $receiverParty,
    ) {
    }
}
