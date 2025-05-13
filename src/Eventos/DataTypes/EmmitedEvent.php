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
        public readonly string $number,
        public readonly EventStatus $status,
        public readonly GovernmentResponse $governmentResponse,
        public readonly ?string $prefix = null,
        public readonly ?string $fullNumber = null,
        public readonly ?string $xmlFileName = null,
        public readonly ?string $pdfFileName = null,
    ) {}
}
