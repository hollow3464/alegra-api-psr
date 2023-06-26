<?php

namespace SaveColombia\AlegraApiPsr\Eventos\DataTypes;

use DateTimeInterface;

final class EmmitedEvent
{
    public function __construct(
        public readonly string $id,
        public readonly EventType $type,
        public readonly DateTimeInterface $date,
        public readonly LegalStatus $legalStatus,
        public readonly string $companyIdentification,
        public readonly string $cude,
        public readonly string $associatedDocumentId,
        public readonly Receiver $receiver,
        public readonly string $prefix,
        public readonly string $number,
        public readonly string $fullNumber,
        public readonly GovernmentResponse $governmentResponse,
        public readonly string $xmlFileName,
        public readonly string $pdfFileName,
        public readonly EventStatus $status,
    ) {
    }
}
