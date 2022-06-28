<?php

namespace App\Infrastructure\Repository;

use App\Domain\Transformation\Transformation;
use App\Domain\Transformation\TransformationRepositoryInterface;
use App\Domain\TransformationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Ramsey\Uuid\UuidInterface;

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

        $cache = $this->entityManager->getConfiguration()->getResultCache();
        $cache->clear();
    }

    public function findAll(): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('T')
            ->where('T.transformationType = :type')
            ->setParameter(':type', TransformationType::ORIGINAL->name);

        $query = $queryBuilder->getQuery()->enableResultCache();

        return $query->getResult();
    }

    public function findAllByImageId(UuidInterface $imageId): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('T', 'T.transformationType')
            ->where('T.imageId = :imageId')
            ->setParameter(':imageId', (string)$imageId);

        $query = $queryBuilder->getQuery()->enableResultCache();

        return $query->getResult();
    }

    public function search(array $filter): array
    {
        $queryBuilder = $this->repository->createQueryBuilder('T');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->in('T.id', ':filter'))
            ->setParameter('filter', $filter);

        $query = $queryBuilder->getQuery()->enableResultCache();

        return $query->getResult();
    }

    public function searchByQuery(string $searchTerm): array
    {
        $sql = "SELECT t.id, t.image_id as imageId, t.image_filename as imageFilename 
                FROM image JOIN transformation t on image.id = t.image_id
                WHERE description LIKE CONCAT('%', :value, '%') 
                   OR tags LIKE CONCAT('%', :value, '%')
                   OR :value LIKE CONCAT('%', transformation_type, '%')";

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Domain\Transformation\Transformation', 't');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'imageFilename', 'imageFilename');
        $rsm->addFieldResult('t', 'imageId', 'imageId');

        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('value', $searchTerm);

        $query->enableResultCache();

        return $query->getResult();
    }
}
