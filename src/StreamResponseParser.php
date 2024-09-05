<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

use LLM\Agents\OpenAI\Client\Exception\LimitExceededException;
use LLM\Agents\OpenAI\Client\Exception\RateLimitException;
use LLM\Agents\OpenAI\Client\Exception\TimeoutException;
use LLM\Agents\OpenAI\Client\Parsers\ParserInterface;
use LLM\Agents\LLM\Exception\LLMException;
use LLM\Agents\LLM\Response\Response;
use OpenAI\Responses\StreamResponse;
use Psr\Http\Message\ResponseInterface;

final class StreamResponseParser
{
    private array $parsers = [];

    public function registerParser(string $type, ParserInterface $parser): void
    {
        $this->parsers[$type] = $parser;
    }

    /**
     * @throws LimitExceededException
     * @throws RateLimitException
     * @throws TimeoutException
     */
    public function parse(StreamResponse $stream, ?StreamChunkCallbackInterface $callback = null): Response
    {
        $this->validateStreamResponse($stream);

        $headers = $this->getHeaders($stream);

        $responseClass = $this->getResponseClass($stream);

        foreach ($this->parsers as $type => $parser) {
            if ($responseClass === $type) {
                return $parser->parse($stream, $callback);
            }
        }

        throw new LLMException(
            \sprintf(
                'Parser not found for response class: %s',
                $responseClass,
            ),
        );
    }

    private function getHeaders(StreamResponse $stream): array
    {
        $headers = [];
        $response = $this->fetchResponse($stream);
        foreach ($response->getHeaders() as $name => $values) {
            $value = $response->getHeaderLine($name);

            // mapping value type
            if (\is_numeric($value)) {
                $value = (float) $value;
            }

            $headers[$name] = $value;
        }

        return $headers;
    }

    private function validateStreamResponse(StreamResponse $stream): void
    {
        $response = $this->fetchResponse($stream);
        if ($response->getStatusCode() !== 200) {
            try {
                $error = \json_decode($response->getBody()->getContents());
            } catch (\Throwable) {
                throw new LLMException(
                    \sprintf(
                        'OpenAI API returned status code %s',
                        $response->getStatusCode(),
                    ),
                    $response->getStatusCode(),
                );
            }

            $message = $error->error->message;
            if ($message === '') {
                $message = $error->error->code;
            }

            throw new LLMException($message);
        }
    }

    private function fetchResponse(StreamResponse $response): ResponseInterface
    {
        $closure = \Closure::bind(function (StreamResponse $class) {
            return $class->response;
        }, null, StreamResponse::class);

        return $closure($response);
    }

    public function getResponseClass(StreamResponse $stream): mixed
    {
        // todo: find a better way to get response class
        $reflection = new \ReflectionClass($stream);
        $responseClass = $reflection->getProperty('responseClass');
        $responseClass->setAccessible(true);
        $responseClass = $responseClass->getValue($stream);
        return $responseClass;
    }
}
