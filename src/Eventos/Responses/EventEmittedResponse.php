<?php

namespace SaveColombia\AllegraApiPsr\Eventos\Responses;

use DateTimeInterface;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\EventStatus;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\EventType;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\LegalStatus;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\Receiver;

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
