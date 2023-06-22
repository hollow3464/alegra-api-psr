<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use DateTimeInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventType;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\LegalStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Receiver;

final class EventEmittedResponse
{
    public function __construct(
        public readonly string $id,
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
    ) {
    }
}
