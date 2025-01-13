<?php

namespace AcMarche\Taxe\Repository;

use AcMarche\Taxe\Doctrine\OrmCrudTrait;
use AcMarche\Taxe\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     * @see UserProviderListener::checkPassport
     */
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :username OR user.username = :username')
            ->setParameter('username', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
