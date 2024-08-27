<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

interface StreamChunkCallbackInterface
{
    public function __invoke(?string $chunk, bool $stop, ?string $finishReason = null): void;
}
