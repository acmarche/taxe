<?php

namespace AcMarche\Taxe\Repository;

use AcMarche\Taxe\Doctrine\OrmCrudTrait;
use AcMarche\Taxe\Entity\Nomenclature;
use AcMarche\Taxe\Entity\Taxe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Taxe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxe[]    findAll()
 * @method Taxe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxeRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Taxe::class);
    }

    /**
     * @return Taxe[]
     */
    public function findAllSorted(): array
    {
        $queryBuilder = $this->createQueryBuilder('taxe');

        return
            $queryBuilder
                ->addOrderBy('taxe.position', 'ASC')
                ->addOrderBy('taxe.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }

    /**
     * @return Taxe[] Returns an array of Taxe objects
     */
    public function search(?string $nom, ?Nomenclature $nomenclature): array
    {
        $queryBuilder = $this->createQueryBuilder('taxe');

        if ($nom) {
            $queryBuilder->andWhere('taxe.nom LIKE :nom')
                ->setParameter('nom', '%'.$nom.'%');
        }

        if ($nomenclature instanceof Nomenclature) {
            $queryBuilder->andWhere('taxe.nom LIKE :nomenclature')
                ->setParameter('nomenclature', '%'.$nom.'%');
        }

        return $queryBuilder->orderBy('taxe.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
