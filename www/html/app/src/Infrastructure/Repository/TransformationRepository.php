<?php

namespace App\Infrastructure\Repository;

use App\Domain\EntityNotFoundException;
use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class TransformationRepository implements TransformationRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository(Transformation::class);
    }

    public function save(Transformation $transformation): void
    {
        $cache = $this->entityManager->getConfiguration()->getResultCache();
        $cache->clear();

        $this->entityManager->persist($transformation);
        $this->entityManager->flush();
    }

    public function getById(Uuid $transformationId): Transformation
    {
        $transformation = $this->repository->findOneBy([
            'id' => $transformationId,
        ]);

        if ( ! $transformation instanceof Transformation) {
            throw EntityNotFoundException::forEntityAndIdentifier('Transformation', (string)$transformationId);
        }

        return $transformation;
    }

    public function findAll(): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('T')
            ->where('T.transformationType = :type')
            ->setParameter(':type', TransformationType::THUMBNAIL->name);

        $query = $queryBuilder->getQuery()->enableResultCache();

        return $query->getResult();
    }
}
