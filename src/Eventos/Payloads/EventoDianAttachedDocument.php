<?php

namespace SaveColombia\AllegraApiPsr\Eventos\Payloads;

use JsonSerializable;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\ClaimCode;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\Company;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\Email;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\IssuerParty;
use SaveColombia\AllegraApiPsr\Eventos\DataTypes\TipoEventoDian;

final class EventoDianAttachedDocument implements JsonSerializable
{
    public function __construct(
        public readonly string $number,
        public readonly TipoEventoDian $type,
        public readonly string $xmlContent,
        public readonly ?IssuerParty $issuerParty = null,
        public readonly ?Company $company = null,
        public readonly ?ClaimCode $claimCode = null,
        public readonly ?Email $email = null
    ) {
        if ($type === TipoEventoDian::RECLAMO_FACTURA && !$claimCode) {
            throw new \InvalidArgumentException(
                "Cargo del motivo de rechazo requerido
                en los eventos rechazo de factura
                o documento electrónico"
            );
        }

        if (
            in_array($type, [TipoEventoDian::RECIBO_FACTURA, TipoEventoDian::RECIBO_BIENES])
            && !$issuerParty
        ) {
            throw new \InvalidArgumentException(
                "Es requerido en los eventos Recibo de Factura y Recibo de bienes y/o servicios"
            );
        }
    }

    public function jsonSerialize(): mixed
    {
        $self = [
            'number'     => $this->number,
            'type'       => $this->type->value,
            'xmlContent' => $this->xmlContent
        ];

        if ($this->issuerParty) {
            $self['issuerParty'] = $this->issuerParty->jsonSerialize();
        }

        if ($this->company) {
            $self['company'] = $this->company->jsonSerialize();
        }

        if ($this->claimCode) {
            $self['claimCode'] = $this->claimCode->value;
        }

        if ($this->email) {
            $self['email'] = $this->email;
        }

        return $self;
    }
}
