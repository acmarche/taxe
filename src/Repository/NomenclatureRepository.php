<?php

namespace AcMarche\Taxe\Repository;

use AcMarche\Taxe\Entity\Nomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nomenclature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomenclature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomenclature[]    findAll()
 * @method Nomenclature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NomenclatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Nomenclature::class);
    }

    /**
     * @return Nomenclature[] Returns an array of Nomenclature objects
     */
    public function findAllGrouped(): array
    {
        return $this->createQueryBuilder('nomenclature')
            ->leftJoin('nomenclature.taxes', 'taxes', 'WITH')
            ->leftJoin('taxes.exercices', 'exercices', 'WITH')
            ->addSelect('taxes', 'exercices')
            ->addOrderBy('nomenclature.position', 'ASC')
            ->addOrderBy('taxes.position', 'ASC')
            ->addOrderBy('exercices.annee', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
