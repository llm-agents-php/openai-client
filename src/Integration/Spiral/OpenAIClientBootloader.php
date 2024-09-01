<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Integration\Spiral;

use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\OpenAI\Client\LLM;
use LLM\Agents\OpenAI\Client\Parsers\ChatResponseParser;
use LLM\Agents\OpenAI\Client\StreamResponseParser;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use Spiral\Boot\Bootloader\Bootloader;

final class OpenAIClientBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            LLMInterface::class => LLM::class,

            StreamResponseParser::class => static function (
                ChatResponseParser $chatResponseParser,
            ): StreamResponseParser {
                $parser = new StreamResponseParser();

                // Register parsers here
                $parser->registerParser(CreateStreamedResponse::class, $chatResponseParser);

                return $parser;
            },
        ];
    }
}