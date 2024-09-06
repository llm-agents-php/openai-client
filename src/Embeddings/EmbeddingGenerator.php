<?php

declare(strict_types=1);

namespace LLM\Agents\OpenAI\Client\Embeddings;

use LLM\Agents\Embeddings\Document;
use LLM\Agents\Embeddings\Embedding;
use LLM\Agents\Embeddings\EmbeddingGeneratorInterface;
use OpenAI\Contracts\ClientContract;

final readonly class EmbeddingGenerator implements EmbeddingGeneratorInterface
{
    public function __construct(
        private ClientContract $client,
        private OpenAIEmbeddingModel $model = OpenAIEmbeddingModel::TextEmbeddingAda002,
    ) {}

    public function generate(Document ...$documents): array
    {
        $documents = \array_values($documents);

        $response = $this->client->embeddings()->create([
            'model' => $this->model->value,
            'input' => \array_map(static fn(Document $doc): string => $doc->content, $documents),
        ]);

        foreach ($response->embeddings as $i => $embedding) {
            $documents[$i] = $documents[$i]->withEmbedding(new Embedding($embedding->embedding));
        }

        return $documents;
    }
}