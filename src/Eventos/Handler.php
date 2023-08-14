<?php

namespace SaveColombia\AlegraApiPsr\Eventos;

use CuyZ\Valinor\MapperBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\TipoArchivoEvento;
use SaveColombia\AlegraApiPsr\Eventos\Payloads\EventoDian;
use SaveColombia\AlegraApiPsr\Eventos\Payloads\EventoDianAttachedDocument;
use SaveColombia\AlegraApiPsr\Eventos\Responses\AttachmentEventEmittedResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\DuplicateEventResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\EventEmittedResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\EventsListResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\FailedRequestResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\FileResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ForbiddenErrorResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ResourceNotFoundResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ServerCommunicationErrorResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ServerErrorResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ValidationErrorResponse;
use SaveColombia\AlegraApiPsr\Exceptions\FailedRequestException;

final class Handler
{
    private string $basePath = "/e-provider/col/v1";
    private readonly string $endpoint;

    public function __construct(
        private readonly ClientInterface $clientInterface,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly UriFactoryInterface $uriFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly MapperBuilder $mapperBuilder,
        private readonly ?LoggerInterface $logger = null,
        private readonly string $token = '',
        bool $testing = true,
    ) {
        $endpoint = "https://sandbox-api.alegra.com";

        if (!$testing) {
            $endpoint = "https://api.alegra.com";
        }

        $this->endpoint = $endpoint;
    }

    private function setupPath(string $path): UriInterface
    {
        $path = ltrim(rtrim($path, '/'), '/');

        return $this->uriFactory
            ->createUri($this->endpoint)
            ->withPath("{$this->basePath}/{$path}");
    }

    private function addAuthToken(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', "Bearer {$this->token}");
    }

    public function emitirEvento(EventoDian $evento): EventEmittedResponse
    {
        $payload = (string) json_encode($evento, JSON_THROW_ON_ERROR);

        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath('events'))
                ->withBody($this->streamFactory->createStream($payload))
                ->withHeader('Content-type', 'application/json')
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            401     => $mapper->map(ForbiddenErrorResponse::class, $body),
            default => null
        };

        if (!$response) {
            if ($this->logger) {
                $this->logger->warning(
                    'Envio de evento a la DIAN fallido',
                    [
                        'endpoint'      => '/events',
                        'response_body' => $body,
                        'response_code' => $res->getStatusCode(),
                    ]
                );
            }

            throw new \Exception("Se recibió un estado desconocido desde la API DIAN");
        }

        if ($response instanceof EventEmittedResponse) {
            if (
                str_contains(
                    $response->event->governmentResponse->errorMessages[0] ?? '',
                    'Documento procesado anteriormente'
                ) ||
                str_contains(
                    $response->event->governmentResponse->errorMessages[0] ?? '',
                    'LGC01'
                )
            ) {
                $response = new DuplicateEventResponse();
            }
        }

        // AEP11005 - Duplicate Event DIAN Response Code
        if ($response instanceof ValidationErrorResponse) {
            if (($response->getErrors()[0] ?? null)?->code === 'AEP11005') {
                $response = new DuplicateEventResponse();
            }
        }

        if ($response instanceof FailedRequestResponse) {
            if ($this->logger) {
                $this->logger->warning(
                    'Envio de evento a la DIAN fallido',
                    [
                        'endpoint'      => '/events',
                        'response_body' => $body,
                        'response_code' => $res->getStatusCode(),
                    ]
                );
            }

            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function emitirEventoAttachedDocument(
        EventoDianAttachedDocument $evento
    ): AttachmentEventEmittedResponse {
        $payload = (string) json_encode($evento, JSON_THROW_ON_ERROR);

        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath('events/from-xml'))
                ->withBody($this->streamFactory->createStream($payload))
                ->withHeader('Content-type', 'application/json')
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200, 201 => $mapper->map(AttachmentEventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            401     => $mapper->map(ForbiddenErrorResponse::class, $body),
            default => null
        };

        if (!$response) {
            if ($this->logger) {
                $this->logger->warning(
                    'Envio de evento a la DIAN fallido',
                    [
                        'endpoint'      => '/events/from-xml',
                        'response_body' => $body,
                        'response_code' => $res->getStatusCode(),
                    ]
                );
            }

            throw new \Exception("Se recibió un estado desconocido desde la API DIAN");
        }

        if ($response instanceof AttachmentEventEmittedResponse) {
            if (
                str_contains(
                    $response->event->governmentResponse->errorMessages[0] ?? '',
                    'Documento procesado anteriormente'
                ) ||
                str_contains(
                    $response->event->governmentResponse->errorMessages[0] ?? '',
                    'LGC01'
                )
            ) {
                $response = new DuplicateEventResponse();
            }
        }

        // AEP11005 - Duplicate Event DIAN Response Code
        if ($response instanceof ValidationErrorResponse) {
            if (($response->getErrors()[0] ?? null)?->code === 'AEP11005') {
                $response = new DuplicateEventResponse();
            }
        }

        if ($response instanceof FailedRequestResponse) {
            if ($this->logger) {
                $this->logger->warning(
                    'Envio de evento a la DIAN fallido',
                    [
                        'endpoint'      => '/events/from-xml',
                        'response_body' => $body,
                        'response_code' => $res->getStatusCode(),
                    ]
                );
            }

            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function consultarEvento(string $id): EventEmittedResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('GET', $this->setupPath("events/{$id}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200, 201 => $mapper->map(EventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            401     => $mapper->map(ForbiddenErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function obtenerArchivoEvento(string $id, TipoArchivoEvento $tipo): FileResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('GET', $this->setupPath("events/{$id}/files/{$tipo->value}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200, 201 => $mapper->map(FileResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            401     => $mapper->map(ForbiddenErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function consultarEventos(string $cufe): EventsListResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('GET', $this->setupPath("events/invoice/{$cufe}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventsListResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            503     => $mapper->map(ServerCommunicationErrorResponse::class, $body),
            401     => $mapper->map(ForbiddenErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }
}
