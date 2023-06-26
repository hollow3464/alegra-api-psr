<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

use DateTimeInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\EventType;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\LegalStatus;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Receiver;

final class AttachmentEventEmittedResponse
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
        public readonly string $number,
        public readonly string $fullNumber,
        public readonly GovernmentResponse $governmentResponse,
        public readonly string $xmlFileName,
        public readonly string $zipFileName,
        public readonly EventStatus $status,
        public readonly ?string $prefix = null,
    ) {
    }
}
