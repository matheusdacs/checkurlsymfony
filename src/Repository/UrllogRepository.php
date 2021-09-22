<?php

namespace App\Repository;

use App\Entity\Urllog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Url;
use App\Entity\User;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Urllog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Urllog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Urllog[]    findAll()
 * @method Urllog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrllogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Urllog::class);
    }

    public function findByUser($user_id): array
    {
        return $this->createQueryBuilder('ul')
            ->innerJoin('ul.url', 'url')
            ->innerJoin('url.user', 'usr')
            ->where("usr.id = :value")
            ->setParameter("value", $user_id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUserAndLog($user_id, $url_id): ?Urllog
    {
        return $this->createQueryBuilder('ul')
            ->innerJoin('ul.url', 'url')
            ->innerJoin('url.user', 'usr')
            ->where("usr.id = :value")
            ->andWhere("ul.id = :url_id")
            ->setParameter("value", $user_id)
            ->setParameter("url_id", $url_id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
