<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Integration\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LLM\Agents\Embeddings\EmbeddingGeneratorInterface;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\OpenAI\Client\Embeddings\EmbeddingGenerator;
use LLM\Agents\OpenAI\Client\Embeddings\OpenAIEmbeddingModel;
use LLM\Agents\OpenAI\Client\LLM;
use LLM\Agents\OpenAI\Client\Parsers\ChatResponseParser;
use LLM\Agents\OpenAI\Client\StreamResponseParser;
use OpenAI\Contracts\ClientContract;
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
            EmbeddingGeneratorInterface::class,
            EmbeddingGenerator::class,
        );

        $this->app->singleton(
            EmbeddingGenerator::class,
            static function (
                ClientContract $client,
            ): EmbeddingGenerator {
                return new EmbeddingGenerator(
                    client: $client,
                    // todo: use config
                    model: OpenAIEmbeddingModel::TextEmbeddingAda002,
                );
            },
        );

        $this->app->singleton(
            StreamResponseParser::class,
            static function (Application $app): StreamResponseParser {
                $parser = new StreamResponseParser();

                // Register parsers here
                $parser->registerParser(
                    CreateStreamedResponse::class,
                    $app->make(ChatResponseParser::class),
                );

                return $parser;
            },
        );
    }
}