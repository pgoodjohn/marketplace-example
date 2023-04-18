<?php

namespace App\Repository;

use App\Entity\MollieApplicationConfiguration;
use App\Mollie\OAuthApplicationConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MollieApplicationConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method MollieApplicationConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method MollieApplicationConfiguration[]    findAll()
 * @method MollieApplicationConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MollieApplicationConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MollieApplicationConfiguration::class);
    }

    public function hasConfiguredApplication(): bool
    {
        try {
            $this->getLastConfiguredApplication();
            return true;
        } catch (\Throwable $e) {
        }

        return false;
    }


    public function getLastConfiguredApplication(): OAuthApplicationConfiguration
    {
        $results = $this->createQueryBuilder('q')
            ->orderBy('q.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (count($results) !== 1) {
            throw new \LogicException(sprintf("Error when fetching last configured application, expected 1 but got %s", count($results)));
        }

        return OAuthApplicationConfiguration::fromEntity($results[0]);
    }

    public function save(OAuthApplicationConfiguration $newAppConfiguration): void
    {
        $newEntity = new MollieApplicationConfiguration();
        $newEntity->setRedirectUrl($newAppConfiguration->getRedirectUrl());
        $newEntity->setClientId($newAppConfiguration->getClientId());
        $newEntity->setClientSecret($newAppConfiguration->getClientSecret());

        $this->getEntityManager()->persist($newEntity);
        $this->getEntityManager()->flush();
    }
}
