<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

use LLM\Agents\LLM\OptionsFactoryInterface;
use LLM\Agents\LLM\OptionsInterface;

final class OptionsFactory implements OptionsFactoryInterface
{
    public function create(array $options = []): OptionsInterface
    {
        return new Options($options);
    }
}
