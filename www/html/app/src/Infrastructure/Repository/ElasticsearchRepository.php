<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ElasticsearchInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Psr\Log\LoggerInterface;

final class ElasticsearchRepository implements ElasticsearchInterface
{
    private const INDEX_NAME = 'images-index';

    private Client $elasticsearchClient;
    private LoggerInterface $logger;

    public function __construct(Client $elasticsearchClient, LoggerInterface $logger)
    {
        $this->elasticsearchClient = $elasticsearchClient;
        $this->logger              = $logger;
    }

    public function add(string $transformationId, string $description, array $tags): void
    {
        try {
            $this->elasticsearchClient->index([
                'index' => self::INDEX_NAME,
                'id'    => $transformationId,
                'body'  => [
                    'description' => $description,
                    'tags'        => $tags
                ]
            ]);
        } catch (ClientResponseException|ServerResponseException|MissingParameterException $ex) {
            $this->logger->error('ElasticsearchException', ['ex' => $ex->getMessage()]);
        }
    }

    public function search(string $query): array
    {
        try {
            $result = $this->elasticsearchClient->search([
                'index' => self::INDEX_NAME,
                'body'  => [
                    'query' => [
                        'multi_match' => [
                            'query'     => $query,
                            'fields'    => ['description', 'tags'],
                            'fuzziness' => 1
                        ]
                    ]
                ]
            ]);

            $data = [];
            foreach ($result->asArray()['hits']['hits'] as $result) {
                $data[] = $result['_id'];
            }

            return $data;
        } catch (ClientResponseException|ServerResponseException $ex) {
            $this->logger->error('ElasticsearchException', ['ex' => $ex->getMessage()]);
        }

        return [];
    }
}