<?php

namespace SaveColombia\AlegraApiPsr\Eventos\Responses;

final class GovernmentResponse
{
    /**
     * @param string $code Código de respuesta de la DIAN
     * @param string $message Mensaje de respuesta de la DIAN
     * @param array<string> $errorMessages mensajes de error devueltos por la DIAN
     */
    public function __construct(
        public readonly string $code,
        public readonly string $message,
        public readonly array $errorMessages = []
    ) {
    }
}
