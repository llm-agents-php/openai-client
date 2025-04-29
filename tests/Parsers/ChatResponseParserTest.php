<?php

declare(strict_types=1);

namespace LLM\Tests\Parsers;

use ArrayIterator;
use LLM\Agents\OpenAI\Client\Parsers\ChatResponseParser;
use OpenAI\Contracts\ResponseStreamContract;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;
use Traversable;
use OpenAI\Responses\Chat\CreateStreamedResponseDelta;
use OpenAI\Responses\Chat\CreateStreamedResponseChoice;
use OpenAI\Responses\Chat\CreateStreamedResponse;

class ChatResponseParserTest extends TestCase
{
    private function makeDelta(?string $content): object
    {
        $toolCalls = [];
        $deltaClass = new ReflectionClass(CreateStreamedResponseDelta::class);
        $delta = $deltaClass->newInstanceWithoutConstructor();
        $deltaClass->getProperty('content')->setValue($delta, $content);
        $deltaClass->getProperty('role')->setValue($delta, null);
        $deltaClass->getProperty('toolCalls')->setValue($delta, $toolCalls);
        return $delta;
    }

    private function makeChoice(object $delta, $finishReason = null): object
    {
        $choiceClass = new ReflectionClass(CreateStreamedResponseChoice::class);
        $choice = $choiceClass->newInstanceWithoutConstructor();
        $choiceClass->getProperty('delta')->setValue($choice, $delta);
        $choiceClass->getProperty('finishReason')->setValue($choice, $finishReason);
        return $choice;
    }

    private function makeChunk(array $choices): object
    {
        $chunkClass = new ReflectionClass(CreateStreamedResponse::class);
        $chunk = $chunkClass->newInstanceWithoutConstructor();
        $chunkClass->getProperty('choices')->setValue($chunk, $choices);
        return $chunk;
    }

    private function makeStream(array $chunks): object
    {
        return new class($chunks) implements ResponseStreamContract {
            private array $chunks;
            public function __construct(array $chunks) { $this->chunks = $chunks; }
            public function getIterator(): Traversable { return new ArrayIterator($this->chunks); }
        };
    }

    public function test_parse_with_mock_stream_contract_returns_expected_response(): void
    {
        $chunk1 = $this->makeChunk([
            $this->makeChoice($this->makeDelta('Hello, world!'))
        ]);
        $chunk2 = $this->makeChunk([
            $this->makeChoice($this->makeDelta(null), 'stop')
        ]);
        $mockStream = $this->makeStream([$chunk1, $chunk2]);

        $mockDispatcher = $this->createMock(EventDispatcherInterface::class);
        $parser = new ChatResponseParser($mockDispatcher);
        $result = $parser->parse($mockStream);

        $this->assertSame('Hello, world!', $result->content);
        $this->assertSame('stop', $result->finishReason);
    }
}
