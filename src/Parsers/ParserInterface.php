<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Parsers;

use LLM\Agents\OpenAI\Client\StreamChunkCallbackInterface;
use LLM\Agents\LLM\Response\Response;
use OpenAI\Contracts\ResponseStreamContract;

interface ParserInterface
{
    public function parse(ResponseStreamContract $stream, ?StreamChunkCallbackInterface $callback = null): Response;
}
