<?php

namespace Hollow3464\Alegra\Eventos\DataTypes;

/**
 * Detalles de la organización que emite el evento,
 * en caso de omitir este objeto se usará la compañia principal asociada al usuario
 */
final readonly class Company implements \JsonSerializable
{
    /**
     * @param string $id
     * Id de la empresa
     *
     * @param OrganizationType $organizationType
     * Tipo de organización jurídica.
     * Se debe colocar el código que corresponda de la tabla de tipos de organización jurídica de la DIAN.
     * Persona Jurídica y asimiladas (1).
     * Persona Natural y asimiladas (2)
     *
     * @param IdentificationType $identificationType,
     * Tipo de documento de identificación del emisor.
     * Se debe colocar el Código que corresponda de la tabla de tipos de identificación de la DIAN
     *
     * @param string $identificationNumber
     * Número de identificación o NIT del emisor, sin guiones ni dv
     *
     * @param string $name
     * Nombre o nombre comercial del emisor
     *
     * @param RegimeCode $regimeCode,
     * Régimen al que pertenece el Emisor.
     * Gran contribuyente (O-13).
     * Autorretenedor (O-15).
     * Agente de retencion IVA (O-23).
     * Régimen simple de tributación (O-47).
     * No aplica - Otros (R-99-PN)
     *
     * @param TaxCode $taxCode
     * Código tipo de impuesto según los códigos de la tabla de la DIAN.
     * IVA (01).
     * INC (04).
     * IVA e INC (ZA).
     * No aplica (ZZ)
     *
     * @param ?string $dv
     * DV del NIT del emisor. Es obligatorio si identificationType = 31
     */
    public function __construct(
        public string $id,
        public ?OrganizationType $organizationType = null,
        public ?IdentificationType $identificationType = null,
        public ?string $identificationNumber = null,
        public ?string $name = null,
        public ?RegimeCode $regimeCode = null,
        public ?TaxCode $taxCode = null,
        public ?string $dv = null,
    ) {
        if ($identificationType === IdentificationType::NIT && !$dv) {
            throw new \Exception(
                "El DV es obligatorio para el tipo de identificación 31, equivalente a NIT"
            );
        }
    }

    public function jsonSerialize(): mixed
    {
        $self = (array) $this;
        return array_filter($self, fn ($value) => $value !== null);
    }
}
