<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Integration\Spiral;

use Illuminate\Contracts\Foundation\Application;
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

            StreamResponseParser::class => static function (Application $app): StreamResponseParser {
                $parser = new StreamResponseParser();

                // Register parsers here
                $parser->registerParser(
                    CreateStreamedResponse::class,
                    $app->make(ChatResponseParser::class)
                );

                return $parser;
            },
        ];
    }
}