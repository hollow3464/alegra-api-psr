<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use DateTimeInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventListEvent;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventType;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\GovernmentResponse;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\LegalStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Receiver;

final class EventsListResponse
{
    /**
     * @param array<string> $errorMessages
     * @param array<EventListEvent> $events
     */
    public function __construct(
        public readonly ?bool $isValid,
        public readonly ?string $statusCode,
        public readonly ?string $statusDescription,
        public readonly ?array $errorMessages = [],
        public readonly ?array $events = [],
        public readonly ?EventType $type = null,
        public readonly ?DateTimeInterface $date = null,
        public readonly ?LegalStatus $legalStatus = null,
        public readonly ?int $companyIdentification = null,
        public readonly ?string $cufe = null,
        public readonly ?string $associatedDocumentId = null,
        public readonly ?Receiver $receiver = null,
        public readonly ?string $prefix = null,
        public readonly ?float $number = null,
        public readonly ?string $fullNumber = null,
        public readonly ?GovernmentResponse $governmentResponse = null,
        public readonly ?string $xmlFileName = null,
        public readonly ?string $pdfFileName = null,
        public readonly ?EventStatus $status = null,
    ) {
    }
}
