<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function add(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function verif_disp_emprunt($id_livre,$date_debut,$date_retour)
    {
        $query = $this->createQueryBuilder('e')
                ->where('e.livre = :id_livre')
                ->setParameter('id_livre', $id_livre)
                ->andWhere(
                            'e.date_sortie between :date_debut AND :date_retour OR
                            e.date_retour between :date_debut AND :date_retour OR
                            :date_debut between e.date_sortie AND e.date_retour OR
                            :date_retour between e.date_sortie AND e.date_retour'
                )
                ->setParameter('date_debut', $date_debut)
                ->setParameter('date_retour', $date_retour)
                ->getQuery()
                ->getResult()
                ;
                return $query;

    } 

    public function emprunt_depasser($date_courant)
    {
        $query = $this->createQueryBuilder('e')
                ->andWhere('e.date_retour < :date_courant')
                ->setParameter('date_courant', $date_courant)
                ->getQuery()
                ->getResult()
                ;
                return $query;

    } 
















//    /**
//     * @return Emprunt[] Returns an array of Emprunt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
