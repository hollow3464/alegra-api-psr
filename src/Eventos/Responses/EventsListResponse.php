<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use DateTimeInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AlegraError;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventListEvent;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventType;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\GovernmentResponse;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\LegalStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Receiver;

final class EventsListResponse
{
    /**
     * @param array<AlegraError> $errorMessages
     * @param array<EventListEvent> $events
     */
    public function __construct(
        public readonly bool $isValid,
        public readonly string $statusCode,
        public readonly string $statusDescription,
        public readonly EventType $type,
        public readonly DateTimeInterface $date,
        public readonly LegalStatus $legalStatus,
        public readonly int $companyIdentification,
        public readonly string $cufe,
        public readonly string $associatedDocumentId,
        public readonly Receiver $receiver,
        public readonly string $prefix,
        public readonly float $number,
        public readonly string $fullNumber,
        public readonly GovernmentResponse $governmentResponse,
        public readonly string $xmlFileName,
        public readonly string $pdfFileName,
        public readonly EventStatus $status,
        public readonly array $events,
        public readonly array $errorMessages,
    ) {
    }
}
