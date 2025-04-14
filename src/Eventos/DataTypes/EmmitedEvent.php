<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;


final class EmmitedEvent
{
    public function __construct(
        public readonly string $id,
        public readonly EventType $type,
        public readonly string $date,
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
