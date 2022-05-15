<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getSearchedUsers($searchData)
    {
      $queryBuild = $this->createQueryBuilder('u');

      return $queryBuild->select()
        ->where($queryBuild->expr()->like('u.first_name', ':searchData'))
        ->orWhere($queryBuild->expr()->like('u.last_name', ':searchData'))
        ->orWhere($queryBuild->expr()->like('u.city', ':searchData'))
        ->orWhere($queryBuild->expr()->like('u.street', ':searchData'))
        ->orWhere($queryBuild->expr()->like('u.email', ':searchData'))
        ->orWhere($queryBuild->expr()->like('u.country', ':searchData'))
        // https://stackoverflow.com/questions/67725384/symfony-find-user-by-role-json-array-doctrine-property
        ->orWhere("JSON_CONTAINS(u.roles, :searchData, :jsonPath)")
        ->setParameter('jsonPath', '$[]')
        ->setParameter('searchData', $searchData)
        ->getQuery()
        ->getResult();
    }

  public function getSearchedUsersQuery($searchData)
  {
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
      'SELECT u 
       FROM App\Entity\User u
       WHERE u.first_name = :searchData
       OR u.last_name = :searchData
       OR u.city = :searchData
       OR u.street = :searchData
       OR u.email = :searchData
       OR u.country = :searchData
       OR JSON_EXTRACT(u.roles, :jsonPath) = :searchData
       OR u.status = UPPER(:searchData)'
    )->setParameter('searchData', $searchData)
    ->setParameter('jsonPath', '$[0]');


    return $query->getResult();
  }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
