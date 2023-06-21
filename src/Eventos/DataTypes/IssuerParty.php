<?php

namespace SaveColombia\AllegraApiPsr\Eventos\DataTypes;

use JsonSerializable;

/**
 * Informa quién recibió la factura electrónica o el bien y servicio.
 * Solo es requerido en los eventos Recibo de Factura y Recibo de bienes y/o servicios
 */
final class IssuerParty implements JsonSerializable
{
    /**
     * @param IdentificationType $identificationType Tipo de identificador fiscal
     * @param string $identificationNumber Número de identificación de la persona
     * @param string $firstName Nombre de la persona que recibió la factura o los bienes y/o servicios
     * @param string $familyName Apellido de la persona que recibió la factura o los bienes y/o servicios
     * @param string $jobTitle Cargo de la persona que recibió la factura o los bienes y/o servicios
     * @param string $organizationDepartment Area, sección o departamento de la persona que recibió la factura o los bienes y/o servicios
     * @param ?string $dv Requerido si el tipo de identificador es 31 equivalente a NIT
     */
    public function __construct(
        public readonly IdentificationType $identificationType,
        public readonly string $identificationNumber,
        public readonly string $firstName,
        public readonly string $familyName,
        public readonly string $jobTitle,
        public readonly string $organizationDepartment,
        public readonly ?string $dv = null,
    ) {
        if ($identificationType === IdentificationType::NIT && !$dv) {
            throw new \Exception(
                "El DV es obligatorio para el tipo de identificación 31, equivalente a NIT"
            );
        }
    }

    public function jsonSerialize(): mixed
    {
        $self = [
            'identificationType'     => $this->identificationType->value,
            'identificationNumber'   => $this->identificationNumber,
            'firstName'              => $this->firstName,
            'familyName'             => $this->familyName,
            'jobTitle'               => $this->jobTitle,
            'organizationDepartment' => $this->organizationDepartment
        ];

        if ($this->dv) {
            $self['dv'] = $this->dv;
        }

        return $self;
    }
}
