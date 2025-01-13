<?php

namespace AcMarche\Taxe\Repository;

use AcMarche\Taxe\Entity\Exercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exercice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercice[]    findAll()
 * @method Exercice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Exercice::class);
    }

}
