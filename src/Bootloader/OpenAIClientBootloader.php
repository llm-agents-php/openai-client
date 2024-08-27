<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Bootloader;

use GuzzleHttp\Client as HttpClient;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\OpenAI\Client\LLM;
use LLM\Agents\OpenAI\Client\Parsers\ChatResponseParser;
use LLM\Agents\OpenAI\Client\StreamResponseParser;
use OpenAI\Contracts\ClientContract;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;

final class OpenAIClientBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            LLMInterface::class => LLM::class,

            ClientContract::class => static fn(
                EnvironmentInterface $env,
            ): ClientContract => \OpenAI::factory()
                ->withApiKey($env->get('OPENAI_KEY'))
                ->withHttpHeader('OpenAI-Beta', 'assistants=v1')
                ->withHttpClient(
                    new HttpClient([
                        'timeout' => (int) $env->get('OPENAI_HTTP_CLIENT_TIMEOUT', 2 * 60),
                    ]),
                )
                ->make(),

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