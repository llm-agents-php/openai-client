<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Event;

final readonly class MessageChunk
{
    public function __construct(
        public string $chunk,
        public bool $stop,
        public ?string $finishReason = null,
    ) {}
}
