<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

use LLM\Agents\LLM\ContextInterface;
use LLM\Agents\LLM\LLMInterface;
use LLM\Agents\LLM\OptionsInterface;
use LLM\Agents\LLM\Prompt\Chat\MessagePrompt;
use LLM\Agents\LLM\Prompt\Chat\PromptInterface as ChatPromptInterface;
use LLM\Agents\LLM\Prompt\PromptInterface;
use LLM\Agents\LLM\Prompt\Tool;
use LLM\Agents\LLM\Response\Response;
use LLM\Agents\OpenAI\Client\Exception\LimitExceededException;
use LLM\Agents\OpenAI\Client\Exception\RateLimitException;
use LLM\Agents\OpenAI\Client\Exception\TimeoutException;
use LLM\Agents\Tool\ToolChoice;
use OpenAI\Contracts\ClientContract;

final class LLM implements LLMInterface
{
    private array $defaultOptions = [
        Option::Temperature->value => 0.8,
        Option::MaxTokens->value => 120,
        Option::TopP->value => null,
        Option::FrequencyPenalty->value => null,
        Option::PresencePenalty->value => null,
        Option::Stop->value => null,
        Option::LogitBias->value => null,
        Option::FunctionCall->value => null,
        Option::Functions->value => null,
        Option::User->value => null,
        Option::Model->value => OpenAIModel::Gpt4oMini->value,
    ];

    public function __construct(
        private readonly ClientContract $client,
        private readonly MessageMapper $messageMapper,
        protected readonly StreamResponseParser $streamParser,
    ) {}

    public function generate(
        ContextInterface $context,
        PromptInterface $prompt,
        OptionsInterface $options,
    ): Response {
        \assert($options instanceof Options);

        $request = $this->buildOptions($options);

        if ($prompt instanceof ChatPromptInterface) {
            $messages = $prompt->format();
        } else {
            $messages = [
                MessagePrompt::user($prompt)->toChatMessage(),
            ];
        }

        foreach ($messages as $message) {
            $request['messages'][] = $this->messageMapper->map($message);
        }

        if ($options->has(Option::Tools->value)) {
            $request = $this->configureTools($options, $request);
        }

        $callback = null;
        if ($options->has(Option::StreamChunkCallback->value)) {
            $callback = $options->get(Option::StreamChunkCallback->value);
            \assert($callback instanceof StreamChunkCallbackInterface);
        }

        $stream = $this
            ->client
            ->chat()
            ->createStreamed($request);

        try {
            return $this->streamParser->parse($stream, $callback);
        } catch (LimitExceededException) {
            throw new \LLM\Agents\LLM\Exception\LimitExceededException(
                currentLimit: $request[Option::MaxTokens->value],
            );
        } catch (RateLimitException) {
            throw new \LLM\Agents\LLM\Exception\RateLimitException();
        } catch (TimeoutException) {
            throw new \LLM\Agents\LLM\Exception\TimeoutException();
        }
    }

    protected function buildOptions(OptionsInterface $options): array
    {
        $result = $this->defaultOptions;

        // only keys that present in default options should be replaced
        foreach ($options as $key => $value) {
            if (isset($this->defaultOptions[$key])) {
                $result[$key] = $value;
            }
        }

        if (!isset($result['model'])) {
            throw new \InvalidArgumentException('Model is required');
        }

        // filter out null options
        return \array_filter($result, static fn($value): bool => $value !== null);
    }

    protected function configureTools(OptionsInterface $options, array $request): array
    {
        $tools = \array_values(
            \array_map(
                fn(Tool $tool): array => $this->messageMapper->map($tool),
                $options->get(Option::Tools->value),
            ),
        );

        if ($tools === []) {
            return $request;
        }

        $request['tools'] = $tools;

        $choice = $options->get(Option::ToolChoice->value);
        if ($choice instanceof ToolChoice) {
            $request['tool_choice'] = match (true) {
                $choice->isAuto() => 'auto',
                $choice->isAny() => 'required',
                $choice->isNone() => 'none',
                $choice->isSpecific() => [
                    'type' => 'function',
                    'function' => [
                        'name' => $choice->toolName,
                    ],
                ],
            };
        }

        return $request;
    }
}
