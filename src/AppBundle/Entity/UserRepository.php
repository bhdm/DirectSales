<?php

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Query\ResultSetMapping;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class UserRepository extends NestedTreeRepository
{

    public function getUsers($projectId, $userId)
    {
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:User','u')
            ->leftJoin('u.projects', 'p')
            ->leftJoin('u.parent', 'pp')
            ->where('( u.parent = :userId OR pp.id = :userId OR pp.parent = :userId  OR u.id = :userId ) AND p.id = :projectId')
            ->orderBy('u.created','DESC')
            ->setParameter('userId',$userId)
            ->setParameter('projectId',$projectId);
        return $res->getQuery()->getResult();
    }

}