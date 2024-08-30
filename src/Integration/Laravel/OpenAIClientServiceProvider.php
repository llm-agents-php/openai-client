<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Integration\Laravel;

use Illuminate\Support\ServiceProvider;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\OpenAI\Client\LLM;
use LLM\Agents\OpenAI\Client\Parsers\ChatResponseParser;
use LLM\Agents\OpenAI\Client\StreamResponseParser;
use OpenAI\Responses\Chat\CreateStreamedResponse;

final class OpenAIClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            LLMInterface::class,
            LLM::class,
        );

        $this->app->singleton(
            StreamResponseParser::class,
            static function (
                ChatResponseParser $chatResponseParser,
            ): StreamResponseParser {
                $parser = new StreamResponseParser();

                // Register parsers here
                $parser->registerParser(CreateStreamedResponse::class, $chatResponseParser);

                return $parser;
            },
        );
    }
}