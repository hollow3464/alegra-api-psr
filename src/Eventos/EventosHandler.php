<?php

namespace Hollow3464\Alegra\Eventos;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\TreeMapper;
use Hollow3464\Alegra\Exceptions\DuplicateEventException;
use Hollow3464\Alegra\Exceptions\UnhandledResponseMappingException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Hollow3464\Alegra\Eventos\Payloads\EventoDian;
use Hollow3464\Alegra\Eventos\Payloads\EventoDianAttachedDocument;
use Hollow3464\Alegra\Eventos\Responses\AttachmentEventEmittedResponse;
use Hollow3464\Alegra\Eventos\Responses\EventEmittedResponse;
use Hollow3464\Alegra\Eventos\Responses\EventsListResponse;
use Hollow3464\Alegra\Eventos\Responses\FailedRequestResponse;
use Hollow3464\Alegra\Eventos\Responses\ForbiddenErrorResponse;
use Hollow3464\Alegra\Eventos\Responses\ResourceNotFoundResponse;
use Hollow3464\Alegra\Eventos\Responses\ServerErrorResponse;
use Hollow3464\Alegra\Eventos\Responses\ValidationErrorResponse;
use Hollow3464\Alegra\Exceptions\UnhandledResponseException;
use Hollow3464\Alegra\Exceptions\FailedRequestException;
use Hollow3464\Alegra\Exceptions\ServiceUnavailableException;

final class EventosHandler
{
    private string $basePath = "/e-provider/col/v1";
    private readonly string $endpoint;

    public function __construct(
        private readonly ClientInterface $clientInterface,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly UriFactoryInterface $uriFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly MapperBuilder $mapperBuilder,
        private readonly string $token = '',
        private readonly ?TreeMapper $mapper = null,
        bool $testing = true,
    ) {
        if ($this->mapper === null) {
            $this->mapper = $this->mapperBuilder
                ->allowSuperfluousKeys()
                ->enableFlexibleCasting()
                ->mapper();
        }
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

    /**
    * @throws ServiceUnavailableException
    * @throws UnhandledResponseException
    * @throws UnhandledResponseMappingException
    * @throws DuplicateEventException
    * @throws FailedRequestException
    */
    public function emitirEvento(EventoDian $evento): EventEmittedResponse
    {
        $payload = (string) json_encode($evento, JSON_THROW_ON_ERROR);

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath('events'))
                ->withBody($this->streamFactory->createStream($payload))
                ->withHeader('Content-type', 'application/json')
        );

        $res = $this->clientInterface->sendRequest($req);
        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        try {
            $response = match ($res->getStatusCode()) {
                200,201 => $this->mapper->map(EventEmittedResponse::class, $body),
                400     => $this->mapper->map(ValidationErrorResponse::class, $body),
                401     => $this->mapper->map(ForbiddenErrorResponse::class, $body),
                404     => $this->mapper->map(ResourceNotFoundResponse::class, $body),
                500     => $this->mapper->map(ServerErrorResponse::class, $body),
                503     => throw new ServiceUnavailableException(),
                default => throw new UnhandledResponseException($body, $res->getStatusCode())
            };
        } catch (MappingError $e) {
            throw new UnhandledResponseMappingException($body, $res->getStatusCode(), $e);
        }


        if ($response instanceof EventEmittedResponse) {
            foreach ($response->event->governmentResponse->errorMessages as $errorMessage) {
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
                    throw new DuplicateEventException(
                        response: $response,
                        responseBody: $body,
                        statusCode: $res->getStatusCode(),
                    );
                }
            }
        }

        // AEP11005 - Duplicate Event DIAN Response Code
        if ($response instanceof ValidationErrorResponse) {
            foreach ($response->getErrors() as $error) {
                if ($error->code === 'AEP11005') {
                    throw new DuplicateEventException(
                        response: $response,
                        responseBody: $body,
                        statusCode: $res->getStatusCode(),
                    );
                }
            }
        }

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException(
                response: $response,
                endpoint: '/events',
                body: $body,
                statusCode: $res->getStatusCode(),
            );
        }

        return $response;
    }


    /**
    * @throws ServiceUnavailableException
    * @throws UnhandledResponseException
    * @throws UnhandledResponseMappingException
    * @throws DuplicateEventException
    * @throws FailedRequestException
    */
    public function emitirEventoAttachedDocument(
        EventoDianAttachedDocument $evento
    ): AttachmentEventEmittedResponse {
        $payload = (string) json_encode($evento, JSON_THROW_ON_ERROR);

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('POST', $this->setupPath('events/from-xml'))
                ->withBody($this->streamFactory->createStream($payload))
                ->withHeader('Content-type', 'application/json')
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        try {
            $response = match ($res->getStatusCode()) {
                200, 201 => $this->mapper->map(AttachmentEventEmittedResponse::class, $body),
                400     => $this->mapper->map(ValidationErrorResponse::class, $body),
                401     => $this->mapper->map(ForbiddenErrorResponse::class, $body),
                404     => $this->mapper->map(ResourceNotFoundResponse::class, $body),
                500     => $this->mapper->map(ServerErrorResponse::class, $body),
                503     => throw new ServiceUnavailableException(),
                default => throw new UnhandledResponseException($body, $res->getStatusCode())
            };
        } catch (MappingError $e) {
            throw new UnhandledResponseMappingException($body, $res->getStatusCode(), $e);
        }

        if ($response instanceof AttachmentEventEmittedResponse) {
            $errMessages = $response->event->governmentResponse->errorMessages;
            foreach ($errMessages as $err) {
                if (
                    str_contains($err, 'Documento procesado anteriormente') ||
                    str_contains($err, 'LGC01')
                ) {
                    throw new DuplicateEventException(
                        response: $response,
                        responseBody: $body,
                        statusCode: $res->getStatusCode(),
                    );
                }
            }
        }

        // AEP11005 - Duplicate Event DIAN Response Code
        if ($response instanceof ValidationErrorResponse) {
            foreach ($response->getErrors() as $error) {
                if ($error->code === 'AEP11005') {
                    throw new DuplicateEventException(
                        response: $response,
                        responseBody: $body,
                        statusCode: $res->getStatusCode(),
                    );
                }
            }
        }

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException(
                response: $response,
                responseBody: $body,
                statusCode: $res->getStatusCode(),
                endpoint: '/events/from-xml',
            );
        }

        return $response;
    }

    /**
    * @throws ServiceUnavailableException
    * @throws UnhandledResponseException
    * @throws UnhandledResponseMappingException
    * @throws FailedRequestException
    */
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

        try {
            $response = match ($res->getStatusCode()) {
                200, 201 => $this->mapper->map(EventEmittedResponse::class, $body),
                400     => $this->mapper->map(ValidationErrorResponse::class, $body),
                401     => $this->mapper->map(ForbiddenErrorResponse::class, $body),
                404     => $this->mapper->map(ResourceNotFoundResponse::class, $body),
                500     => $this->mapper->map(ServerErrorResponse::class, $body),
                503     => throw new ServiceUnavailableException(),
                default => throw new UnhandledResponseException($body, $res->getStatusCode())
            };
        } catch (MappingError $e) {
            throw new UnhandledResponseMappingException($body, $res->getStatusCode(), $e);
        }

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }

    /**
    * @throws ServiceUnavailableException
    * @throws UnhandledResponseException
    * @throws UnhandledResponseMappingException
    * @throws FailedRequestException
    */
    public function consultarEventos(string $cufe): EventsListResponse
    {

        $req = $this->addAuthToken(
            $this->requestFactory
                ->createRequest('GET', $this->setupPath("events/invoice/{$cufe}"))
        );

        $res = $this->clientInterface->sendRequest($req);

        $body = json_decode($res->getBody(), true, flags: JSON_THROW_ON_ERROR);

        try {
            $response = match ($res->getStatusCode()) {
                200     => $this->mapper->map(EventsListResponse::class, $body),
                400     => $this->mapper->map(ValidationErrorResponse::class, $body),
                401     => $this->mapper->map(ForbiddenErrorResponse::class, $body),
                404     => $this->mapper->map(ResourceNotFoundResponse::class, $body),
                500     => $this->mapper->map(ServerErrorResponse::class, $body),
                503     => throw new ServiceUnavailableException(),
                default => throw new UnhandledResponseException($body, $res->getStatusCode())
            };
        } catch (MappingError $e) {
            throw new UnhandledResponseMappingException($body, $res->getStatusCode(), $e);
        }

        if ($response instanceof FailedRequestResponse) {
            throw new FailedRequestException($response);
        }

        return $response;
    }
}
