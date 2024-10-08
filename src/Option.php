<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client;

enum Option: string
{
    // Configuration options
    case Temperature = 'temperature';
    case MaxTokens = 'max_tokens';
    case TopP = 'top_p';
    case FrequencyPenalty = 'frequency_penalty';
    case PresencePenalty = 'presence_penalty';
    case Stop = 'stop';
    case LogitBias = 'logit_bias';
    case Functions = 'functions';
    case FunctionCall = 'function_call';
    case User = 'user';
    case Model = 'model';

    // Application options
    case Tools = 'tools';
    case ToolChoice = 'tool_choice';
    case StreamChunkCallback = 'stream_chunk_callback';
}
