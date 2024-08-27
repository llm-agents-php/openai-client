<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

use LLM\Agents\LLM\ContextFactoryInterface;
use LLM\Agents\LLM\ContextInterface;

final class ContextFactory implements ContextFactoryInterface
{
    public function create(): ContextInterface
    {
        return new Context();
    }
}
