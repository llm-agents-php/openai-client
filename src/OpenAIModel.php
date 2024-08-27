<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

enum OpenAIModel: string
{
    case Gpt4o = 'gpt-4o';
    case Gpt4oLatest = 'chatgpt-4o-latest';
    case Gpt4o20240513 = 'gpt-4o-2024-05-13';
    case Gpt4o20240806 = 'gpt-4o-2024-08-06';

    case Gpt4oMini = 'gpt-4o-mini';
    case Gpt4oMini20240718 = 'gpt-4o-mini-2024-07-18';

    case Gpt4Turbo = 'gpt-4-turbo';
    case Gpt4TurboPreview = 'gpt-4-turbo-preview';

    case Gpt4 = 'gpt-4';
    case Gpt40613 = 'gpt-4-0613';

    case Gpt3Turbo = 'gpt-3.5-turbo';
    case Gpt3Turbo1106 = 'gpt-3.5-turbo-1106';
    case Gpt3TurboInstruct = 'gpt-3.5-turbo-instruct';
}