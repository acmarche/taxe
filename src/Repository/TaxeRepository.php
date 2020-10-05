<?php

namespace AcMarche\Taxe\Repository;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxe::class);
    }

    public function findAllSorted()
    {
        $qb = $this->createQueryBuilder('taxe');

        return
            $qb
                ->addOrderBy('taxe.position', 'ASC')
                ->addOrderBy('taxe.nom', 'ASC')
                ->getQuery()
                ->getResult();
    }

    /**
     * @return Taxe[] Returns an array of Taxe objects
     */
    public function search(?string $nom, ?Nomenclature $nomenclature)
    {
        $qb = $this->createQueryBuilder('taxe');

        if ($nom) {
            $qb->andWhere('taxe.nom LIKE :nom')
                ->setParameter('nom', '%' . $nom . '%');
        }
        if ($nomenclature) {
            $qb->andWhere('taxe.nom LIKE :nomenclature')
                ->setParameter('nomenclature', '%' . $nom . '%');
        }
        return $qb->orderBy('taxe.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
