<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

use JsonSerializable;

/**
 * Persona o entidad que recibe el evento, necesaria en todos los eventos a excepción de Aceptación Tácita (034)
 */
final class ReceiverParty implements JsonSerializable
{
    /**
     * @param ?string $name
     * Nombre o razón social del receptor
     *
     * @param ?OrganizationType $organizationType
     * Tipo de organización jurídica.
     * Se debe colocar el Código que corresponda de la tabla de tipos de organización jurídica de la DIAN.
     * Persona Jurídica y asimiladas (1).
     * Persona Natural y asimiladas (2)
     *
     * @param ?string $identificationNumber
     * Número de identificación o NIT del receptor, sin guiones ni DV
     *
     * @param ?IdentificationType $identificationType
     * Tipo de documento de identificación del receptor.
     * Se debe colocar el Código que corresponda de la tabla de tipos de identificación de la DIAN
     *
     * @param ?RegimeCode $regimeCode
     * Régimen al que pertenece el Emisor.
     * Gran contribuyente (O-13).
     * Autorretenedor (O-15).
     * Agente de retencion IVA (O-23).
     * Régimen simple de tributación (O-47).
     * No aplica - Otros (R-99-PN)
     *
     * @param ?TaxCode $taxCode
     * Código tipo de impuesto según los códigos de la tabla de la DIAN.
     * IVA (01).
     * INC (04).
     * IVA e INC (ZA).
     * No aplica (ZZ).

     *
     * @param ?string $dv
     * DV del NIT del receptor. Es obligatorio si idNumberType = 31
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?OrganizationType $organizationType,
        public readonly ?string $identificationNumber,
        public readonly ?IdentificationType $identificationType,
        public readonly ?RegimeCode $regimeCode,
        public readonly ?TaxCode $taxCode,
        public readonly ?string $dv = null,
    ) {
        if ($identificationType === IdentificationType::NIT && !$dv) {
            throw new \Exception(
                "El DV es obligatorio para el tipo de identificación 31,
                equivalente a NIT"
            );
        }
    }

    public function jsonSerialize(): mixed
    {
        $self = [
            'name'                 => $this->name,
            'organizationType'     => $this->organizationType?->value,
            'identificationNumber' => $this->identificationNumber,
            'identificationType'   => $this->identificationType?->value,
            'regimeCode'           => $this->regimeCode?->value,
            'taxCode'              => $this->taxCode?->value
        ];

        if ($this->dv) {
            $self['dv'] = $this->dv;
        }

        return $self;
    }
}
