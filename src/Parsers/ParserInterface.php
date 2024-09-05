<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Parsers;

use LLM\Agents\OpenAI\Client\Exception\LimitExceededException;
use LLM\Agents\OpenAI\Client\Exception\RateLimitException;
use LLM\Agents\OpenAI\Client\Exception\TimeoutException;
use LLM\Agents\OpenAI\Client\StreamChunkCallbackInterface;
use LLM\Agents\LLM\Response\Response;
use OpenAI\Contracts\ResponseStreamContract;

interface ParserInterface
{
    /**
     * @throws LimitExceededException
     * @throws RateLimitException
     * @throws TimeoutException
     */
    public function parse(ResponseStreamContract $stream, ?StreamChunkCallbackInterface $callback = null): Response;
}
