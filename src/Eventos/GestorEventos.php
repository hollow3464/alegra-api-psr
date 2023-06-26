<?php

namespace SaveColombia\AlegraApiPsr\Eventos;

use CuyZ\Valinor\MapperBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use SaveColombia\AlegraApiPsr\Eventos\DataTypes\TipoArchivoEvento;
use SaveColombia\AlegraApiPsr\Eventos\Payloads\EventoDian;
use SaveColombia\AlegraApiPsr\Eventos\Payloads\EventoDianAttachedDocument;
use SaveColombia\AlegraApiPsr\Eventos\Responses\EventEmittedResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\EventsListResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\FailedRequestResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\FileResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ResourceNotFoundResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ServerCommunicationErrorResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ServerErrorResponse;
use SaveColombia\AlegraApiPsr\Eventos\Responses\ValidationErrorResponse;
use SaveColombia\AlegraApiPsr\Exceptions\FailedRequestException;

final class GestorEventos
{
    private readonly string $endpoint;
    private readonly string $basePath;

    public function __construct(
        private readonly ClientInterface $clientInterface,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly UriFactoryInterface $uriFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly MapperBuilder $mapperBuilder,
        private readonly string $token = '',
        bool $testing = true,
    ) {
        $endpoint = "https://sandbox-api.alegra.com";

        if (!$testing) {
            $endpoint = "https://api.alegra.com";
        }

        $this->endpoint = $endpoint;
        $this->basePath = "/e-provider/col/v1";
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

    public function emitirEventoFacturaElectronica(EventoDian $evento): EventEmittedResponse
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
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function emitirEventoFacturaElectronicaAttachedDocument(
        EventoDianAttachedDocument $evento
    ): EventEmittedResponse {
        $payload = (string) json_encode($evento, JSON_THROW_ON_ERROR);

        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath('events'))
                ->withBody($this->streamFactory->createStream($payload))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function consultarEventoFacturaElectronica(string $id): EventEmittedResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath("events/{$id}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventEmittedResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function obtenerArchivoEventoFacturaElectronica(string $id, TipoArchivoEvento $tipo): FileResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath("events/{$id}/files/{$tipo->value}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(FileResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    public function consultarEventosFacturaElectronicaCufe(string $cufe): EventsListResponse
    {
        $mapper = $this->mapperBuilder
            ->allowSuperfluousKeys()
            ->enableFlexibleCasting()
            ->mapper();

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath("events/invoice/{$cufe}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), JSON_THROW_ON_ERROR);

        $response = match ($res->getStatusCode()) {
            200     => $mapper->map(EventsListResponse::class, $body),
            400     => $mapper->map(ValidationErrorResponse::class, $body),
            404     => $mapper->map(ResourceNotFoundResponse::class, $body),
            500     => $mapper->map(ServerErrorResponse::class, $body),
            503     => $mapper->map(ServerCommunicationErrorResponse::class, $body),
            default => throw new \Exception("Se recibió un estado desconocido desde la API", 1)
        };

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }
}
