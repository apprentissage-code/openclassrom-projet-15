<?php

namespace App\Repository;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Media::class);
  }

  public function findByAlbum(Album $album)
  {
    return $this->createQueryBuilder('m')
      ->join('m.user', 'u')
      ->where('m.album = :album')
      ->andWhere('u.isBlocked = false')
      ->setParameter('album', $album)
      ->getQuery()
      ->getResult();
  }

  public function findByUser(User $user)
  {
    return $this->createQueryBuilder('m')
      ->join('m.user', 'u')
      ->where('m.user = :user')
      ->andWhere('u.isBlocked = false')
      ->setParameter('user', $user)
      ->getQuery()
      ->getResult();
  }
}
