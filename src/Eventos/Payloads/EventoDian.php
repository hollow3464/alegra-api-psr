<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Payloads;

use JsonSerializable;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\AssociatedDocument;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\ClaimCode;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Company;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\Email;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\IssuerParty;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\ReceiverParty;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\TipoEventoDian;

final class EventoDian implements JsonSerializable
{
    /**
     *
     * @param string $number
     * Número del evento que se emite,
     * el valor es alfanumérico y único por evento dentro de la misma compañia
     *
     * @param TipoEventoDian $type
     * Código relacionado al tipo de evento que se quiere emitir.
     * Recibo de Factura (030).
     * Reclamo de la Factura Electrónica de Venta (031).
     * Recibo Bienes y Servicios (032).
     * Aceptación Expresa (033).
     * Aceptación Tácita (034).
     *
     * @param AssociatedDocument $associatedDocument
     * Objecto que contiene la información del documento de referencia
     *
     * @param ?IssuerParty $issuerParty
     * Informa quién recibió la factura electrónica o el bien y servicio.
     * Solo es requerido en los eventos Recibo de Factura y Recibo de bienes y/o servicios
     *
     * @param ?ReceiverParty $receiverParty
     * Persona o entidad que recibe el evento,
     * necesaria en todos los eventos a excepción de Aceptación Tácita (034)
     *
     * @param ?Company $company
     * Detalles de la organización que emite el evento,
     * en caso de omitir este objeto se usará la compañia principal asociada al usuario
     *
     * @param ?ClaimCode $claimCode
     * Código del motivo de rechazo según la tabla DIAN.
     * Documento con inconsistencias (01).
     * Mercancía no entregada totalmente (02).
     * Mercancía no entregada parcialmente (03).
     * Servicio no prestado (04).
     * Campo requerido únicamente en los eventos de rechazo de factura o documento electrónico
     *
     * @param ?Email $email
     * Objeto para enviar un email de recepción de documento
     */
    public function __construct(
        public readonly string $number,
        public readonly TipoEventoDian $type,
        public readonly AssociatedDocument $associatedDocument,
        public readonly ?IssuerParty $issuerParty = null,
        public readonly ?ReceiverParty $receiverParty = null,
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

        if ($type !== TipoEventoDian::ACEPTACION_TACITA && !$receiverParty) {
            throw new \InvalidArgumentException(
                "Persona o entidad que recibe el evento,
                necesaria en todos los eventos a excepción
                de Aceptación Tácita (034)"
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
            'number'             => $this->number,
            'type'               => $this->type->value,
            'associatedDocument' => $this->associatedDocument->jsonSerialize()
        ];

        if ($this->issuerParty) {
            $self['issuerParty'] = $this->issuerParty->jsonSerialize();
        }

        if ($this->receiverParty) {
            $self['receiverParty'] = $this->receiverParty->jsonSerialize();
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
