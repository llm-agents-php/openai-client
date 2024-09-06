<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Embeddings;

enum OpenAIEmbeddingModel: string
{
    case TextEmbedding3Small = 'text-embedding-3-small';
    case TextEmbedding3Large = 'text-embedding-3-large';
    case TextEmbeddingAda002 = 'text-embedding-ada-002';
}
