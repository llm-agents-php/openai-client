<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

use LLM\Agents\LLM\OptionsInterface;
use Traversable;

readonly class Options implements OptionsInterface
{
    public function __construct(
        private array $options = [],
    ) {}

    public function withModel(OpenAIModel $model): static
    {
        return $this->with(Option::Model, $model->value);
    }

    public function withTemperature(float $temperature): static
    {
        return $this->with(Option::Temperature, $temperature);
    }

    public function withMaxTokens(int $maxTokens): static
    {
        return $this->with(Option::MaxTokens, $maxTokens);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->options);
    }

    public function has(string|Option $option): bool
    {
        return isset($this->options[$this->prepareKey($option)]);
    }

    public function get(string|Option $option, mixed $default = null): mixed
    {
        return $this->options[$this->prepareKey($option)] ?? $default;
    }

    public function with(string|Option $option, mixed $value): static
    {
        $options = $this->options;
        $options[$this->prepareKey($option)] = $value;

        return new static($options);
    }

    private function prepareKey(string|Option $key): string
    {
        return match (true) {
            $key instanceof Option => $key->value,
            default => $key,
        };
    }

    public function merge(OptionsInterface $options): static
    {
        $mergedOptions = $this->options;

        foreach ($options as $key => $value) {
            $mergedOptions[$key] = $value;
        }

        return new static($mergedOptions);
    }
}
