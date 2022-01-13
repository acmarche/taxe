<?php

namespace AcMarche\Taxe\Repository;

use AcMarche\Taxe\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function persist(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function insert(User $user): void
    {
        $this->persist($user);
        $this->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
